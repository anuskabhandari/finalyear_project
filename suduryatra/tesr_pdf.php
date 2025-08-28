<?php
require 'vendor/autoload.php'; // load dompdf
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml("
    <h1 style='color:green;'>Hello Rekha 👋</h1>
    <p>Dompdf is successfully installed and working! 🎉</p>
");
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream PDF to browser
$dompdf->stream("test.pdf", ["Attachment" => false]); 
// false = open in browser, true = force download
?>
