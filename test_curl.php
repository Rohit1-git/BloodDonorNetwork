<?php

if (function_exists('curl_init')) {
    echo "✅ cURL is ENABLED";
} else {
    echo "❌ cURL is DISABLED";
}

?>