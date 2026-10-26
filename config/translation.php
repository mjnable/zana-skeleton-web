<?php

return [
    'locales' => ['en', 'fr'],   // the first is the fallback
    'cookie' => 'locale',        // the language switcher's choice is remembered
    'redirect_root' => true,     // "/" goes to "/en" or "/fr", by cookie or browser language
];
