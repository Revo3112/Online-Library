<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\BookModel;
use App\Models\AuthModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;

class User extends BaseController
{
    protected $book;
    protected $category;
    protected $userModel;
    protected $userId;
    protected $role;

    public function __construct()
    {
        $this->book = new BookModel();
        $this->category = new CategoryModel();
        $this->userModel = new AuthModel();
        $this->userId = session()->get('user_id');
        $this->role = session()->get('role');

        // Ensure the user is logged in
        if (!$this->userId) {
            // Redirect to login if not authenticated
            return redirect()->to('/login')->send();
        }
    }

    public function dashboard()
    {
        // Check if the user is an admin
        $isAdmin = $this->role === 'admin';

        try {
            // Retrieve books based on the user's role using query builder to prevent SQL injection
            $books = $isAdmin ? $this->book->findAll() : $this->book->where('created_by', $this->userId)->findAll();

            // Retrieve categories based on the user's role
            if ($isAdmin) {
                // Fetch unique category names for admins
                $categories = $this->category->distinct()->select('name')->findAll();
            } else {
                // Fetch categories for the specific user
                $categories = $this->category->where('user_id', $this->userId)->findAll();
            }

            // Retrieve the last book edited by the user or globally if admin
            $lastBook = $isAdmin ? $this->book->orderBy('updated_at', 'desc')->first() : $this->book->where('created_by', $this->userId)->orderBy('updated_at', 'desc')->first();

            // Retrieve user count if admin
            $userCount = $isAdmin ? $this->userModel->countAll() : null;

            // Prepare data to pass to the view
            $data = [
                'dashboard_data' => [
                    'nama' => session()->get('name'),
                    'books' => $books,
                    'categories' => $categories,
                    'last_book' => $lastBook,
                    'user_count' => $userCount
                ]
            ];

            // Load the dashboard view
            return view('user/dashboard', $data);
        } catch (\Exception $e) {
            // Log the error message for further analysis
            log_message('error', $e->getMessage());

            // Redirect to a safe error page or show a generic error message
            return redirect()->to('/error')->with('msg', 'Terjadi kesalahan. Silakan coba lagi nanti.')->with('error', true);
        }
    }


    public function books($categoryId = null)
    {
        $isAdmin = $this->role === 'admin';

        // Retrieve categories based on the user's role
        $categories = $this->category->getUserCategories($this->userId, $isAdmin);

        $builder = $this->book->builder();
        $builder->select('books.*, users.username as author_name, categories.name as category_name');
        $builder->join('users', 'users.id = books.created_by', 'left');
        $builder->join('categories', 'categories.id = books.category_id', 'left');

        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }

        if (!$isAdmin) {
            $builder->where('books.created_by', $this->userId);
        }

        $builder->where('books.deleted_at', null); // Exclude soft-deleted books
        $books = $builder->get()->getResult(); // Use getResult() to get an array of objects

        $data = [
            'title' => 'Buku',
            'books' => $books,
            'categories' => $categories
        ];

        return view('user/books', $data);
    }

    public function editBook($id)
    {
        $book = $this->book->find($id);

        // Ensure book exists and check permission for editing
        if (!$book || ($this->role !== 'admin' && $book['created_by'] != $this->userId)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Buku tidak ditemukan atau Anda tidak memiliki izin untuk mengedit buku ini.");
        }

        // If admin, get categories belonging to the original book creator
        $categories = ($this->role === 'admin')
            ? $this->category->where('user_id', $book['created_by'])->findAll()
            : $this->category->where('user_id', $this->userId)->findAll();

        $data = [
            'book' => $book,
            'categories' => $categories,
            'validation' => \Config\Services::validation()
        ];

        return view('user/edit_book', $data);
    }

    public function updateBook($id)
    {
        // Find the book by its ID and check if it is not soft-deleted
        $book = $this->book->where('deleted_at', null)->find($id);

        // Check if the book exists and the user has permission to update it
        if (!$book || ($this->role !== 'admin' && $book['created_by'] != $this->userId)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Buku tidak ditemukan atau Anda tidak memiliki izin untuk memperbarui buku ini.");
        }

        // Define validation rules
        $validationRules = [
            'title' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer',
            'category_id' => 'required|integer',
            'file_pdf' => 'permit_empty|ext_in[file_pdf,pdf]|max_size[file_pdf,2048]',
            'cover_image' => 'permit_empty|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png]|max_size[cover_image,1024]'
        ];

        // Validate the input data
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        // Get the input data from the request
        $input = $this->request->getPost();
        $pdfFile = $this->request->getFile('file_pdf');
        $coverImage = $this->request->getFile('cover_image');

        // Handle PDF file upload
        if ($pdfFile->isValid() && !$pdfFile->hasMoved()) {
            // If the file is uploaded, check if it's the same as the one in the database
            if ($pdfFile->getName() != $book['file_path'] || md5_file($pdfFile->getTempName()) != md5_file(FCPATH . 'pdfs/' . $book['file_path'])) {
                // The file is different; update and replace
                $pdfFile->move(FCPATH . 'pdfs', $book['file_path'], true); // Overwrite the existing file
                $input['file_path'] = $book['file_path'];
            }
        } else {
            $input['file_path'] = $book['file_path'];
        }

        // Handle cover image upload
        if ($coverImage->isValid() && !$coverImage->hasMoved()) {
            // If the image is uploaded, check if it's the same as the one in the database
            if ($coverImage->getName() != $book['cover_image'] || md5_file($coverImage->getTempName()) != md5_file(FCPATH . 'img/' . $book['cover_image'])) {
                // The image is different; update and replace
                $coverImage->move(FCPATH . 'img', $book['cover_image'], true); // Overwrite the existing image
                $input['cover_image'] = $book['cover_image'];
            }
        } else {
            $input['cover_image'] = $book['cover_image'];
        }

        // Prepare the data for update
        $updateData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'quantity' => $input['quantity'],
            'category_id' => $input['category_id'],
            'file_path' => $input['file_path'],
            'cover_image' => $input['cover_image'],
            // Preserve the original created_by ID unless it's the admin who originally created the book
            'created_by' => ($this->role === 'admin' && $book['created_by'] == $this->userId) ? $this->userId : $book['created_by']
        ];

        // Update the book record
        if ($this->book->update($id, $updateData)) {
            return redirect()->to('universal/books')->with('msg', 'Buku berhasil diperbarui.');
        } else {
            return redirect()->back()->with('msg', 'Gagal memperbarui buku.')->with('error', true);
        }
    }

    public function deleteBook($id)
    {
        $book = $this->book->find($id);
        if (!$book) {
            return $this->response->setJSON(['success' => false, 'message' => 'Buku tidak ditemukan.']);
        }

        if ($this->book->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Buku berhasil dihapus.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus buku.']);
        }
    }

    public function addBook()
    {
        $categories = $this->role === 'admin' ? $this->category->findAll() : $this->category->where('user_id', $this->userId)->findAll();

        $data = [
            'categories' => $categories,
            'validation' => \Config\Services::validation()
        ];

        return view('user/add_book', $data);
    }

    public function createBook()
    {
        $validationRules = [
            'title' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|greater_than_equal_to[0]',
            'category_id' => 'required|integer',
            'file_pdf' => 'uploaded[file_pdf]|ext_in[file_pdf,pdf]|max_size[file_pdf,100048]',
            'cover_image' => 'uploaded[cover_image]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png]|max_size[cover_image,1024]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        $input = $this->request->getPost();
        $pdfFile = $this->request->getFile('file_pdf');
        $coverImage = $this->request->getFile('cover_image');

        if ($pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $newPdfFilename = $pdfFile->getRandomName();
            $pdfFile->move(FCPATH . 'pdfs', $newPdfFilename);
            $input['file_path'] = $newPdfFilename;
        }

        if ($coverImage->isValid() && !$coverImage->hasMoved()) {
            $newCoverFilename = $coverImage->getRandomName();
            $coverImage->move(FCPATH . 'img', $newCoverFilename);
            $input['cover_image'] = $newCoverFilename;
        }

        $input['created_by'] = $this->userId;

        if (!$input['created_by']) {
            return redirect()->back()->withInput()->with('validation', ['created_by' => 'User ID is missing']);
        }

        if ($this->book->save([
            'title' => $input['title'],
            'description' => $input['description'],
            'quantity' => $input['quantity'],
            'category_id' => $input['category_id'],
            'file_path' => $input['file_path'],
            'cover_image' => $input['cover_image'],
            'created_by' => $input['created_by']
        ])) {
            return redirect()->to('universal/books')->with('msg', 'Buku berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('msg', 'Gagal menambahkan buku.')->with('error', true);
        }
    }

    public function viewBook($id)
    {
        $book = $this->book->find($id);
        if (!$book) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Buku tidak ditemukan: " . $id);
        }

        $data = [
            'book' => $book
        ];

        return view('user/view_book', $data);
    }

    public function kategori()
    {
        $db = \Config\Database::connect();

        if ($this->role === 'admin') {
            // Fetch unique categories by name and count books for those categories using query builder
            $builder = $db->table('categories');
            $builder->select('categories.name, COUNT(books.id) as book_count');
            $builder->join('books', 'books.category_id = categories.id AND books.deleted_at IS NULL', 'left');
            $builder->groupBy('categories.name');

            $categories = $builder->get()->getResultArray();
        } else {
            // For non-admin users, fetch only their categories using query builder
            $categories = $this->category->where('user_id', $this->userId)->findAll();
            $bookCountInCategories = [];
            foreach ($categories as $category) {
                $bookCountInCategories[$category['id']] = $this->book
                    ->where('category_id', $category['id'])
                    ->where('deleted_at', null)
                    ->countAllResults();
            }

            // Merge book counts with category data
            foreach ($categories as &$category) {
                $category['book_count'] = $bookCountInCategories[$category['id']] ?? 0;
            }
        }

        $data = [
            'categories' => $categories,
            'itemPerPage' => 10,
            'currentPage' => $this->request->getGet('page') ?? 1
        ];

        return view('user/kategori', $data);
    }

    public function addKategori()
    {
        return view('user/add_kategori');
    }

    public function createKategori()
    {
        $validationRules = [
            'category' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        $categoryName = $this->request->getPost('category');

        // Check if the category already exists for the user using query builder
        $existingCategory = $this->category
            ->where('name', $categoryName)
            ->where('user_id', $this->userId)
            ->first();

        if ($existingCategory) {
            return redirect()->back()->withInput()->with('msg', 'Kategori sudah ada.')->with('error', true);
        }

        $categoryData = [
            'name' => $categoryName,
            'user_id' => $this->userId,
        ];

        if ($this->category->save($categoryData)) {
            return redirect()->to('universal/kategori')->with('msg', 'Kategori berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('msg', 'Gagal menambahkan kategori.')->with('error', true);
        }
    }

    public function editKategori($identifier)
    {
        // Check if the identifier is numeric (ID) or a string (name)
        if (is_numeric($identifier)) {
            $category = $this->category->find($identifier);
        } else {
            $category = $this->category->where('name', urldecode($identifier))->first();
        }

        if (!$category) {
            return redirect()->to('universal/kategori')->with('msg', 'Kategori tidak ditemukan.')->with('error', true);
        }

        $data = [
            'category' => $category,
            'validation' => \Config\Services::validation()
        ];

        return view('user/edit_kategori', $data);
    }

    public function updateKategori($identifier)
    {
        $validationRules = [
            'category' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        $categoryName = $this->request->getPost('category');

        // Determine if the identifier is a name or an ID based on the role
        $isUsingName = session()->get('role') === 'admin';

        // Use query builder to prevent SQL injection
        $builder = $this->category->builder();

        // Check if the category already exists for the user (excluding the current category)
        $builder->where('name', $categoryName)
            ->where('user_id', $this->userId);

        if ($isUsingName) {
            $builder->where('name !=', $identifier);
        } else {
            $builder->where('id !=', $identifier);
        }

        $existingCategory = $builder->get()->getRow();

        if ($existingCategory) {
            return redirect()->back()->withInput()->with('msg', 'Kategori sudah ada.')->with('error', true);
        }

        // Get the category based on identifier (name or id)
        if ($isUsingName) {
            $category = $this->category->where('name', $identifier)->first();
        } else {
            $category = $this->category->find($identifier);
        }

        if (!$category) {
            return redirect()->back()->with('msg', 'Kategori tidak ditemukan.')->with('error', true);
        }

        // Check for books with non-zero quantity associated with this category
        $affectedBooks = $this->book
            ->where('category_id', $category['id'])
            ->where('quantity >', 0)
            ->findAll();

        $categoryData = [
            'name' => $categoryName,
            'user_id' => $this->userId,
        ];

        if ($this->category->update($category['id'], $categoryData)) {
            // Prepare the message about affected books
            $affectedBooksCount = count($affectedBooks);
            $message = 'Kategori berhasil diperbarui.';

            if ($affectedBooksCount > 0) {
                $message .= " Ada {$affectedBooksCount} buku yang terpengaruh oleh kategori ini.";
            }

            return redirect()->to('universal/kategori')->with('msg', $message);
        } else {
            return redirect()->back()->with('msg', 'Gagal memperbarui kategori.')->with('error', true);
        }
    }

    public function deleteKategori($identifier)
    {
        $userRole = $this->role;
        $userId = $this->userId;

        if ($userRole === 'admin') {
            // Jika admin, cek apakah admin adalah pembuat kategori
            $category = $this->category->where('name', $identifier)->where('user_id', $userId)->first();

            if ($category) {
                // Jika admin adalah pembuat, hapus berdasarkan id
                $categoryId = $category['id'];
            } else {
                // Jika admin bukan pembuat, cari kategori berdasarkan nama
                $category = $this->category->where('name', $identifier)->first();
                if ($category) {
                    $categoryId = $category['id'];
                }
            }
        } else {
            // Jika user, hapus berdasarkan id
            $category = $this->category->find($identifier);
            if ($category) {
                $categoryId = $category['id'];
            }
        }

        if (!$category) {
            return redirect()->to('universal/kategori')->with('msg', 'Kategori tidak ditemukan.')->with('error', true);
        }

        $affectedBooks = $this->book
            ->where('category_id', $categoryId)
            ->where('quantity >', 0)
            ->findAll();

        if ($this->category->delete($categoryId)) {
            $affectedBooksCount = count($affectedBooks);
            $message = 'Kategori berhasil dihapus.';

            if ($affectedBooksCount > 0) {
                $message .= " Ada {$affectedBooksCount} buku yang terpengaruh oleh kategori ini.";
            }

            return redirect()->to('universal/kategori')->with('msg', $message);
        } else {
            return redirect()->to('universal/kategori')->with('msg', 'Gagal menghapus kategori.')->with('error', true);
        }
    }


    public function booksByCategory($categoryName)
    {
        // Fetch books with author names for the given category name using query builder
        $builder = $this->book->builder();
        $builder->select('books.*, users.username as author_name, categories.name as category_name');
        $builder->join('users', 'users.id = books.created_by', 'left');
        $builder->join('categories', 'categories.id = books.category_id', 'left');
        $builder->where('categories.name', $categoryName);
        $builder->where('books.deleted_at', null);

        // Apply user filter if not an admin
        if ($this->role !== 'admin') {
            $builder->where('books.created_by', $this->userId);
        }

        // Execute the query and get the result
        $books = $builder->get()->getResultArray();

        // Prepare data for the view
        $data = [
            'title' => 'Buku dalam Kategori ' . $categoryName,
            'books' => $books,
            'categoryName' => $categoryName,
            'role' => $this->role,
            'userId' => $this->userId,
        ];

        // Load the view and pass the data
        return view('user/books_by_category', $data);
    }

    public function search()
    {
        $searchTerm = $this->request->getGet('search');
        $isAdmin = $this->role === 'admin';
        $userId = $this->userId;

        $builder = $this->book->builder();
        $builder->select('books.*, categories.name as category_name, users.username as author_name')
            ->join('categories', 'categories.id = books.category_id', 'left')
            ->join('users', 'users.id = books.created_by', 'left')
            ->groupStart() // Start group for orWhere conditions
            ->like('books.title', $searchTerm)
            ->orLike('categories.name', $searchTerm)
            ->orLike('users.username', $searchTerm)
            ->groupEnd() // End group
            ->where('books.deleted_at', null);

        // If the user is not an admin, restrict the search to their own books
        if (!$isAdmin) {
            $builder->where('books.created_by', $userId);
        }

        $books = $builder->get()->getResult();

        $data = [
            'books' => $books,
            'searchTerm' => $searchTerm,
            // Include categories if needed for the dropdown
            'categories' => $this->category->findAll()
        ];

        // Load the books view with the search results
        return view('user/books', $data);
    }

    public function search2($categoryName)
    {
        $searchTerm = $this->request->getGet('search');
        $isAdmin = $this->role === 'admin';
        $userId = $this->userId;

        // Get category based on category name
        $category = $this->category->where('name', $categoryName)->first();

        if (!$category) {
            return redirect()->to('universal/kategori')->with('msg', 'Kategori tidak ditemukan.')->with('error', true);
        }

        $builder = $this->book->builder();
        $builder->select('books.*, categories.name as category_name, users.username as author_name')
            ->join('categories', 'categories.id = books.category_id', 'left')
            ->join('users', 'users.id = books.created_by', 'left')
            ->where('books.deleted_at', null);

        if ($isAdmin) {
            // Admins can search by category name
            $builder->where('categories.name', $categoryName)
                ->groupStart()
                ->like('books.title', $searchTerm)
                ->orLike('users.username', $searchTerm)
                ->groupEnd();
        } else {
            // Regular users can search only in their own books by category ID
            $builder->where('categories.id', $category['id'])
                ->where('books.created_by', $userId)
                ->groupStart()
                ->like('books.title', $searchTerm)
                ->orLike('users.username', $searchTerm)
                ->groupEnd();
        }

        $books = $builder->get()->getResultArray();

        $data = [
            'title' => 'Buku dalam Kategori: ' . $categoryName,
            'categoryName' => $categoryName,
            'books' => $books,
            'searchTerm' => $searchTerm,
            'role' => $this->role,
            'userId' => $userId
        ];

        // Load the books view with the search results
        return view('user/books_by_category', $data);
    }



    public function exportBooks()
    {
        $userId = session()->get('user_id');
        $isAdmin = (session()->get('role') === 'admin');

        // Configure the query based on the user's role
        $builder = $this->book->builder();
        $builder->select('books.*, categories.name as category_name, users.username as author_name');
        $builder->join('categories', 'categories.id = books.category_id');
        $builder->join('users', 'users.id = books.created_by');

        if (!$isAdmin) {
            $builder->where('books.created_by', $userId);
        }

        $books = $builder->get()->getResult();

        // Load the view for PDF generation
        $html = view('books_pdf', ['books' => $books]);

        // Setup Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("books_export.pdf", array("Attachment" => true));
    }


    public function exportBooksToExcel()
    {
        $isAdmin = ($this->role === 'admin');
        $userId = $this->userId;  // Assume this is set from session or a similar user context

        $selectFields = 'books.id, books.title, books.description, categories.name as category_name, books.created_at, books.updated_at';
        if ($isAdmin) {
            $selectFields .= ', books.deleted_at';
        }

        // Begin building the query
        $query = $this->book->select($selectFields)
            ->join('categories', 'categories.id = books.category_id');

        if (!$isAdmin) {
            // Limit to books created by the user if not an admin
            $query->where('books.created_by', $userId);
        }

        $books = $query->findAll();

        // Load PhpSpreadsheet classes
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $headers = ['ID', 'Title', 'Description', 'Category', 'Created At', 'Updated At'];
        if ($isAdmin) {
            $headers[] = 'Deleted At';
        }
        $sheet->fromArray($headers, NULL, 'A1');

        // Fill data rows
        $rowNumber = 2;
        foreach ($books as $book) {
            $rowData = [
                $book['id'],
                $book['title'],
                $book['description'],
                $book['category_name'],
                $book['created_at'],
                $book['updated_at']
            ];
            if ($isAdmin) {
                $rowData[] = $book['deleted_at'] ?? 'N/A';
            }
            $sheet->fromArray($rowData, NULL, 'A' . $rowNumber);
            $rowNumber++;
        }

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="books.xlsx"');

        // Save and serve file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function users()
    {
        // Fetch users where the role is 'user'
        $users = $this->userModel->where('role', 'user')->findAll();

        // Initialize an array to hold user data with book counts
        $usersWithBookCounts = [];

        // Iterate over each user to calculate book count
        foreach ($users as $user) {
            $bookCount = $this->book->where('created_by', $user['id'])->countAllResults();
            $usersWithBookCounts[] = [
                'id' => $user['id'],
                'username' => $user['username'],  // Use 'username'
                'email' => $user['email'],
                'book_count' => $bookCount
            ];
        }

        $data = [
            'users' => $usersWithBookCounts
        ];

        // Load the users view
        return view('admin/users', $data);
    }

    public function searchUsers()
    {
        // Check if the user is an admin
        if ($this->role !== 'admin') {
            return redirect()->to('/login');
        }

        // Get the search term from the GET request
        $searchTerm = $this->request->getGet('search');

        // Perform the search on users based on the search term
        $users = $this->userModel->select('id, username, email')
            ->like('username', $searchTerm)
            ->orLike('email', $searchTerm)
            ->findAll();

        // Initialize an array to hold user data with book counts
        $usersWithBookCounts = [];

        // Iterate over each user to calculate book count
        foreach ($users as $user) {
            $bookCount = $this->book->where('created_by', $user['id'])->countAllResults();
            $usersWithBookCounts[] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'book_count' => $bookCount
            ];
        }

        // Pass the search term and the users data to the view
        $data = [
            'users' => $usersWithBookCounts,
            'searchTerm' => $searchTerm ?? '', // Initialize searchTerm if not set
        ];

        return view('admin/users', $data);
    }


    public function serveFile($filename)
    {
        $path = FCPATH . 'pdfs/' . urldecode($filename);
        if (file_exists($path) && is_file($path)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            readfile($path);
            exit;
        } else {
            return redirect()->to('/404');
        }
    }
    public function deleteUser($id)
    {
        // Ensure the user is not deleting themselves
        if ($id == $this->userId) {
            return redirect()->to('admin/users')->with('msg', 'Tidak dapat menghapus akun Anda sendiri.')->with('error', true);
        }

        // Attempt to delete the user
        if ($this->userModel->delete($id)) {
            return redirect()->to('admin/users')->with('msg', 'Pengguna berhasil dihapus.');
        } else {
            return redirect()->to('admin/users')->with('msg', 'Gagal menghapus pengguna.')->with('error', true);
        }
    }
}
