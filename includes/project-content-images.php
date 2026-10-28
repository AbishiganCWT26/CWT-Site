<?php

class ProjectContentImageException extends RuntimeException {}

/** Store Quill's embedded images as files before sending the HTML to MySQL. */
function storeProjectContentImages(string $content): string {
    if (stripos($content, 'data:image/') === false) return $content;

    $document = new DOMDocument('1.0', 'UTF-8');
    $wrapperId = 'cwt-content-' . bin2hex(random_bytes(8));
    $previousErrors = libxml_use_internal_errors(true);
    try {
        $loaded = $document->loadHTML('<?xml encoding="UTF-8"><div id="' . $wrapperId . '">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        if (!$loaded) throw new ProjectContentImageException('The content could not be read. Please try inserting the image again.');
        $wrapper = $document->getElementById($wrapperId);
        if (!$wrapper) throw new ProjectContentImageException('The content could not be read. Please try inserting the image again.');
        $extensions = ['image/png'=>'png', 'image/jpeg'=>'jpg', 'image/gif'=>'gif', 'image/webp'=>'webp'];
        foreach ($wrapper->getElementsByTagName('img') as $image) {
            $source = $image->getAttribute('src');
            if (stripos($source, 'data:') !== 0) continue;
            if (!preg_match('~\Adata:image/(?:png|jpe?g|gif|webp);base64,(.*)\z~is', $source, $match)) {
                throw new ProjectContentImageException('Please use JPG, PNG, GIF or WebP images in project content.');
            }
            // Reject oversized data before allocating its decoded copy.
            if (strlen($match[1]) > 7 * 1024 * 1024) throw new ProjectContentImageException('Each content image must be 5 MB or smaller.');
            $bytes = base64_decode($match[1], true);
            if ($bytes === false || strlen($bytes) > 5 * 1024 * 1024) {
                throw new ProjectContentImageException('A content image is invalid or larger than 5 MB. Please insert a smaller image.');
            }
            $info = @getimagesizefromstring($bytes);
            $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($bytes);
            if (!$info || !isset($extensions[$mime]) || ($info['mime'] ?? '') !== $mime) {
                throw new ProjectContentImageException('A content image could not be opened. Please insert a valid JPG, PNG, GIF or WebP image.');
            }
            $relative = 'projects/inline-' . hash('sha256', $bytes) . '.' . $extensions[$mime];
            $directory = UPLOAD_DIR . 'projects';
            if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new ProjectContentImageException('The content image could not be saved. Please check the uploads/projects folder permissions.');
            }
            $path = UPLOAD_DIR . $relative;
            if (!is_file($path) && file_put_contents($path, $bytes, LOCK_EX) !== strlen($bytes)) {
                throw new ProjectContentImageException('The content image could not be saved. Please try again.');
            }
            // Width and data-image-align stay on the image unchanged.
            $image->setAttribute('src', UPLOAD_URL . $relative);
        }
        $result = '';
        foreach ($wrapper->childNodes as $child) $result .= $document->saveHTML($child);
        return $result;
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);
    }
}
