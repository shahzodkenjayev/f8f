<?php
// Ko'p tilli tizimni yuklash
require_once 'includes/language.php';

// Ma'lumotlar bazasiga ulanish faylini chaqirib olamiz
require 'includes/db_connect.php';

// Loyihaning asosiy papkasini ko'rsatamiz
$base_url = '/ff.uz/';

// Kategoriyalarni bazadan olish uchun so'rov
$categories_sql = "SELECT id, name_uz, name_ru FROM categories ORDER BY name_uz ASC"; // Barcha kategoriyalarni olamiz
$categories_result = $conn->query($categories_sql);

// Ommabop (yoki oxirgi qo'shilgan) 4 ta mahsulotni olish uchun so'rov
$featured_products_sql = "SELECT id, name_uz, name_ru, image, price, unit FROM products ORDER BY id DESC LIMIT 4";
$featured_products_result = $conn->query($featured_products_sql);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('site_title'); ?></title>
    <meta name="description" content="<?php echo t('site_description'); ?>">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
</head>
<body>

    <?php 
    // Saytning yuqori qismini (header) chaqiramiz
    include 'includes/header.php'; 
    ?>

    <section class="hero">
        <div class="hero-content">
            <h1><?php echo t('hero_title'); ?></h1>
            <p><?php echo t('hero_subtitle'); ?></p>
            <a href="<?php echo $base_url; ?>products.php" class="btn"><?php echo t('hero_button'); ?></a>
        </div>
    </section>

    <section class="advantages">
        <div class="container">
            <h2><?php echo t('advantages_title'); ?></h2>
            <div class="advantages-grid">
                <div class="advantage-item">
                    <img src="<?php echo $base_url; ?>assets/images/icons/quality.svg" alt="<?php echo t('advantage_quality_title'); ?>">
                    <h3><?php echo t('advantage_quality_title'); ?></h3>
                    <p><?php echo t('advantage_quality_desc'); ?></p>
                </div>
                <div class="advantage-item">
                    <img src="<?php echo $base_url; ?>assets/images/icons/consultation.svg" alt="<?php echo t('advantage_consultation_title'); ?>">
                    <h3><?php echo t('advantage_consultation_title'); ?></h3>
                    <p><?php echo t('advantage_consultation_desc'); ?></p>
                </div>
                <div class="advantage-item">
                    <img src="<?php echo $base_url; ?>assets/images/icons/delivery.svg" alt="<?php echo t('advantage_delivery_title'); ?>">
                    <h3><?php echo t('advantage_delivery_title'); ?></h3>
                    <p><?php echo t('advantage_delivery_desc'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="categories-section">
        <div class="container">
            <h2><?php echo t('categories_title'); ?></h2>
            <div class="categories-grid">
                <?php
                                 if ($categories_result->num_rows > 0) {
                     while($row = $categories_result->fetch_assoc()) {
                         $category_name = $current_language == 'ru' ? $row['name_ru'] : $row['name_uz'];
                         echo '<a href="' . $base_url . 'products.php?category_id=' . $row['id'] . '" class="category-card">';
                         echo '<h3>' . htmlspecialchars($category_name) . '</h3>';
                         echo '</a>';
                     }
                 } else {
                     echo '<p>' . ($current_language == 'ru' ? 'Категории не найдены.' : 'Kategoriyalar topilmadi.') . '</p>';
                 }
                ?>
            </div>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2><?php echo t('featured_products_title'); ?></h2>
            <div class="products-grid">
                <?php
                                 if ($featured_products_result->num_rows > 0) {
                     while($row = $featured_products_result->fetch_assoc()) {
                         $product_name = $current_language == 'ru' ? $row['name_ru'] : $row['name_uz'];
                         echo '<div class="product-card">';
                         // Rasm mavjudligini tekshiramiz
                         $image_path = 'assets/images/products/' . htmlspecialchars($row["image"]);
                         if (!empty($row["image"]) && file_exists($image_path)) {
                             echo '<img src="' . $base_url . $image_path . '" alt="' . htmlspecialchars($product_name) . '">';
                         } else {
                             // Agar rasm bo'lmasa, placeholder ko'rsatamiz
                             echo '<div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #ccc;">📷</div>';
                         }
                         echo '<h3>' . htmlspecialchars($product_name) . '</h3>';
                        
                                                 // Narx ko'rsatish
                         if (!empty($row['price'])) {
                             echo '<div class="product-price" style="margin: 10px 0; padding: 8px 12px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 8px; border-left: 4px solid #0d47a1; display: flex; align-items: baseline; gap: 5px;">';
                             echo '<span style="font-size: 1.2rem; font-weight: 700; color: #0d47a1;">' . number_format($row['price'], 0, ',', ' ') . '</span>';
                             echo '<span style="font-size: 0.8rem; color: #666; font-weight: 500;">' . t('currency') . '/' . htmlspecialchars($row['unit'] ?? 'kg') . '</span>';
                             echo '</div>';
                         }
                        
                        echo '<a href="' . $base_url . 'product-detail.php?id=' . $row["id"] . '" class="btn-secondary">' . t('view_details') . '</a>';
                        echo '</div>';
                    }
                                 } else {
                     echo '<p>' . ($current_language == 'ru' ? 'Продукты не найдены.' : 'Mahsulotlar topilmadi.') . '</p>';
                 }
                ?>
            </div>
        </div>
    </section>

    <?php 
    // Saytning quyi qismini (footer) chaqiramiz
    include 'includes/footer.php'; 
    
    // Ma'lumotlar bazasi bilan ulanishni yopamiz
    $conn->close();
    ?>

</body>
</html>
