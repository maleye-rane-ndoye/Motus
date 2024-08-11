<?php
namespace App\Controllers;

class HomeController {
    public function showHomePage() {
        require __DIR__ . '/../Views/homePage.php';
    }


    public function showRulesPage() {
        require __DIR__ . '/../Views/rules.php';
    }
}
