<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books PDF Export</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            width: 60px;
            height: 90px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <h1>Books List</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Cover</th>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Author</th>
                <th>Link File</th>
                <th>Created At</th>
                <th>Updated At</th>
                <?php if (session()->get('role') === 'admin'): ?>
                    <th>Deleted At</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($books) && (is_iterable($books) || is_object($books))): ?>
                <?php foreach ($books as $index => $book): ?>
                    <tr>
                        <td><?= esc($index + 1); ?></td>
                        <td>
                            <?php $imgPath = FCPATH . 'img/' . $book->cover_image; ?>
                            <?php if (file_exists($imgPath)): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents($imgPath)); ?>" alt="Cover Image">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($book->title); ?></td>
                        <td><?= esc($book->description); ?></td>
                        <td><?= esc($book->category_name); ?></td>
                        <td><?= esc($book->author_name); ?></td>
                        <td>
                            <a href="<?= base_url('pdfs/' . $book->file_path); ?>" download="<?= esc($book->title); ?>.pdf">Download PDF</a>
                        </td>
                        <td><?= esc($book->created_at); ?></td>
                        <td><?= esc($book->updated_at); ?></td>
                        <?php if (session()->get('role') === 'admin'): ?>
                            <td><?= esc($book->deleted_at ?? 'N/A'); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>