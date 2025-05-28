<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED)); 
class Config{
    public static function DB_NAME(){
        return "sneakerstore";
    }
    public static function DB_PORT(){
        return 3306;
    }
    public static function DB_USER(){
        return "root";
    }
    public static function DB_PASSWORD(){
        return "1234";
    }
    public static function DB_HOST(){
        return "127.0.0.1";
    }

    public static function JWT_SECRET() {
       return '283c48618a6305a36b0653f77bb4ac1a7d41ed53870bf48993388f4a824a8e304781218da88e7057be449aaa8d883d2d9dc23668795b5517c881f4ba0cc11c54a1adc0afb153e4354b514e338bc670e963c013445f5431920f8c58d506b5e200994f41f216a895814bd294050360be4c219a47653ccd441f6324fd441e26984864cf9c3d3c815daab7433365d9a154a4c95faea84b93603de45e18977a107c6ec546d258db024564f9430e9146f4ecf58ffd3a2c9958ef738eec1f5585b2f7cc1b4f2f63d4260a1ef2e179eff421b353cfcf7c0809d7303a250961e395185a72c4241b4faaa673883c8ff2878d8619b7ab7be62e31d1cc6370bf88811d0d9992';
   }

}

