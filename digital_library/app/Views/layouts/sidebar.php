<?php
$sidebarnav = [
    [
        'title' => 'Dashboard',
        'icon' => 'ti ti-dashboard',
        'link' => '/universal/home'
    ],
    [
        'title' => 'Buku',
        'icon' => 'ti ti-book',
        'link' => '/universal/books'
    ],
    [
        'title' => 'Kategori',
        'icon' => 'ti ti-filter',
        'link' => '/universal/kategori'
    ]
];

// Assuming you have stored user roles in the session
if (session()->get('role') === 'admin') {
    $sidebarnav = array_merge(
        $sidebarnav,
        [
            [
                'title' => 'Manajemen Akun',
                'icon' => 'ti ti-dots', // Use a different icon or omit it if not needed
                'link' => '#', // Use '#' for section headers if no link is required
                'isHeader' => true // Custom flag to identify headers
            ],
            [
                'title' => 'Users',
                'link' => '/admin/users',
                'icon' => 'ti ti-user'
            ]
        ]
    );
}
?>

<aside class="left-sidebar" style="width: max-content;">
    <div>
        <!-- Brand -->
        <div class="brand-logo d-flex align-items-center justify-content-between" style="margin-bottom: 40px; margin-top: 20px;">
            <div class="pt-4 mx-auto">
                <a href="<?= base_url(); ?>">
                    <h2>Perpustakaan<span class="text-primary">Online</span></h2>
                </a>
            </div>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <?php foreach ($sidebarnav as $nav) : ?>
                    <?php if (isset($nav['isHeader']) && $nav['isHeader']) : ?>
                        <!-- Display section headers differently -->
                        <li class="nav-small-cap">
                            <i class="<?= $nav['icon']; ?> nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu"><?= $nav['title']; ?></span>
                        </li>
                    <?php else : ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= base_url($nav['link']) ?>" aria-expanded="false">
                                <span>
                                    <i class="<?= $nav['icon']; ?>"></i>
                                </span>
                                <span class="hide-menu"><?= $nav['title']; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Logout Button -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="<?= base_url('auth/logout') ?>" aria-expanded="false">
                        <span>
                            <i class="ti ti-power-off"></i>
                        </span>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
</aside>