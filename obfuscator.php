<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PHP Code Obfuscator</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f7f7f7;
    }
    .container {
        width: 70%;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    form {
        text-align: center;
    }
    .input-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }
    textarea {
        width: 45%;
        height: 200px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
        box-sizing: border-box;
    }
    input[type="submit"] {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #0056b3;
    }
    .output {
        margin-top: 30px;
        text-align: center;
    }
    .output textarea {
        width: 45%;
        height: 200px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
        box-sizing: border-box;
    }
</style>
</head>
<body>
<div class="container">
    <h2>PHP Code Obfuscator</h2>
    <form method="post" action="">
        <!-- Text area to input PHP code -->
        <div class="input-container">
            <textarea name="php_code" placeholder="Enter your PHP code here..." required><?php echo '<?php echo "Hello, world!"; ?>'; ?></textarea>
        </div>
        <!-- Submit button -->
        <input type="submit" name="obfuscate" value="Obfuscate">
    </form>
    <div class="output">
        <h3>Obfuscated Code:</h3>
        <textarea name="obfuscated_code" readonly><?php
            function generateRandomInteger($length = 20) {
                $min = (int)pow(10, $length-1); // Cast to integer
                $max = (int)pow(10, $length)-1; // Cast to integer
                // Generate a random integer within the specified range
                $random_integer = rand($min, $max);
                // Ensure the generated integer is positive
                return abs($random_integer);
            }

            if(isset($_POST['obfuscate'])) {
                $code = $_POST['php_code'];
                // Split input code into PHP and HTML sections
                preg_match_all('/(?:<\?php)(.*?)(?:\?>|<\?php|$)/s', $code, $matches);
                $php_sections = $matches[1];
                $html_sections = preg_split('/(?:<\?php)(.*?)(?:\?>|<\?php|$)/s', $code);
                $html_sections = array_map('htmlspecialchars', $html_sections); // Escape HTML characters
                
                // Obfuscate PHP sections
                $obfuscated_php_sections = array();
                foreach ($php_sections as $php_section) {
                    // Encode the stripped code
                    $base64_encoded = base64_encode($php_section);
                    // Generate a random 20-digit integer
                    $encryption_key = generateRandomInteger();
                    // Encrypt the base64-encoded code
                    $encrypted_code = openssl_encrypt($base64_encoded, 'aes-256-cbc', strval($encryption_key), 0, str_repeat(chr(0), 16));
                    // Generate the obfuscated code
                    $obfuscated_php_sections[] = '<?php $encryption_key = ' . $encryption_key . '; $encrypted_code = "' . $encrypted_code . '"; $decrypted_code = openssl_decrypt($encrypted_code, "aes-256-cbc", strval($encryption_key), 0, str_repeat(chr(0), 16)); eval(base64_decode($decrypted_code)); ?>';
                }
                
                // Combine obfuscated PHP sections with HTML sections
                $output_sections = array();
                for ($i = 0; $i < count($html_sections) + count($obfuscated_php_sections); $i++) {
                    if ($i % 2 == 0) {
                        $output_sections[] = $html_sections[$i / 2];
                    } else {
                        $output_sections[] = $obfuscated_php_sections[($i - 1) / 2];
                    }
                }
                echo implode('', $output_sections);
            }
        ?></textarea>
    </div>
</div>
</body>
</html>
