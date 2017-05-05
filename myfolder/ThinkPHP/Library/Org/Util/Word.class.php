<?php
require_once (THINK_PATH . '/Library/Vendor/PhpWord/PhpWord.php');
require_once (THINK_PATH . '/Library/Vendor/PhpWord/Settings.php');
require_once (THINK_PATH . '/Library/Vendor/PhpWord/Shared/ZipArchive.php');
require_once (THINK_PATH . '/Library/Vendor/PhpWord/TemplateProcessor.php');

class Word {
    public function __construct($data, $filename) {
        $this->export($data, $filename);
    }
    public function export($data, $filename) {
        $name = time() . 'temp.docx';
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Public/assets/tpl/test.docx');
        
        foreach (unserialize($data['data']) as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }
        
        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }
        $templateProcessor->setValue('today', date('Y-m-d'));
        $templateProcessor->saveAs($name);
        $size = filesize($name);
        header('Content-type:application/octer-stream');
        header('Accept-Ranges:bytes');
        header('Accept-Length:' . $size);
        header('Content-Disposition:attachment; filename="' . $filename . '.docx"');
        echo file_get_contents($name);
        unlink($name);
        exit();
    }
}
?>