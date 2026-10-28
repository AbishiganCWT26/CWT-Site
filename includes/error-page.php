<?php
/** Standalone error screen: deliberately independent of the database and site layout. */
function renderSiteError(int $code, ?string $title = null, ?string $description = null): never {
    $code = $code >= 400 && $code <= 599 ? $code : 500;
    $messages = [
        400 => ['Something looks off.', 'We could not understand that request. Please check it and try again.'],
        401 => ['Please sign in.', 'You need to sign in to access this page.'],
        403 => ['This page is off limits.', 'You do not have access to this page, or your session has expired.'],
        404 => ["No, no, that's right.", 'This is a 404 page. And this page exists, no matter what anyone says.'],
        405 => ['That action is unavailable.', 'This page does not support the requested action.'],
        408 => ['That took a little too long.', 'The request timed out. Please try again.'],
        410 => ['This page has moved on.', 'The content you are looking for is no longer available.'],
        413 => ['That file is a little too big.', 'Please choose a smaller file and try again.'],
        422 => ['Let’s check those details.', 'Some of the information provided is invalid. Please go back and try again.'],
        429 => ['Let’s take a moment.', 'There have been too many requests. Please wait a little and try again.'],
        500 => ['Something went wrong.', 'We could not load this page. Please try again in a moment.'],
        502 => ['We hit a connection problem.', 'An upstream service is unavailable. Please try again shortly.'],
        503 => ['We’ll be right back.', 'The service is temporarily unavailable. Please try again in a moment.'],
        504 => ['That took a little too long.', 'A service did not respond in time. Please try again shortly.'],
    ];
    [$defaultTitle, $defaultDescription] = $messages[$code] ?? ($code >= 500 ? $messages[500] : $messages[400]);
    $escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $title = $escape($title ?? $defaultTitle);
    $description = $escape($description ?? $defaultDescription);
    $root = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $site = str_replace('\\', '/', dirname(__DIR__));
    $base = $root !== '' && strncasecmp($site . '/', $root . '/', strlen($root) + 1) === 0
        ? substr($site, strlen($root)) : '/CWT Site';
    $home = $escape(implode('/', array_map('rawurlencode', explode('/', $base))) . '/index.php');
    http_response_code($code);
    header('Content-Type: text/html; charset=UTF-8');
    header('Cache-Control: no-store');
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="robots" content="noindex"><title>{$code} — Creative Web Technologies</title>
 <style>
 img{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-user-drag:none;-khtml-user-drag:none;-moz-user-drag:none;-o-user-drag:none;user-drag:none;-webkit-touch-callout:none;pointer-events:none;-webkit-tap-highlight-color:transparent}
 *{box-sizing:border-box}body{margin:0;background:#eaf2ff;color:#111a4a;font-family:Poppins,system-ui,-apple-system,sans-serif}
 .error-screen{position:relative;isolation:isolate;min-height:100vh;min-height:100svh;display:grid;place-items:center;padding:64px 24px;overflow:hidden}
 .error-screen::before{content:'';position:absolute;inset:0;z-index:-1;pointer-events:none;opacity:.12;background-image:repeating-linear-gradient(0deg,transparent,transparent 19px,#245bdb 19px,#245bdb 20px,transparent 20px,transparent 39px,#245bdb 39px,#245bdb 40px),repeating-linear-gradient(90deg,transparent,transparent 19px,#245bdb 19px,#245bdb 20px,transparent 20px,transparent 39px,#245bdb 39px,#245bdb 40px),radial-gradient(circle at 20px 20px,#245bdb 2px,transparent 2px),radial-gradient(circle at 40px 40px,#245bdb 2px,transparent 2px);background-size:40px 40px;mask-image:radial-gradient(ellipse at center,#000,transparent 75%)}
 .error-content{width:100%;max-width:512px;text-align:center}.error-code{display:block;width:100%;max-width:448px;margin:0 auto 12px;user-select:none;overflow:visible}
 .error-code text{font-family:Arial,Helvetica,sans-serif;font-size:280px;font-weight:900;letter-spacing:-18px;fill:url(#error-code-gradient);stroke:#245bdb;stroke-width:2;stroke-dasharray:40 20}
 h1{font-size:clamp(22px,4vw,28px);line-height:1.3;letter-spacing:-.035em;margin:0 0 12px;font-weight:750}p{max-width:370px;margin:0 auto;color:#65748b;font-size:15px;line-height:1.8}
 .error-home{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:44px;padding:10px 24px;margin-top:28px;border-radius:999px;background:#245bdb;color:white;text-decoration:none;font-size:14px;font-weight:650;box-shadow:inset 0 2px 4px #ffffff55,inset 0 -2px 4px #0003;transition:background .2s,transform .2s}
 .error-home:hover{background:#194fc7;transform:translateY(-2px)}.error-home:focus-visible{outline:3px solid #245bdb;outline-offset:5px}.error-home svg{width:18px;height:18px;fill:currentColor}
 @media(prefers-reduced-motion:no-preference){.error-code text{animation:error-code-flow 12s linear infinite}.error-content{animation:error-appear .6s ease-out both}}
 @keyframes error-code-flow{to{stroke-dashoffset:-120}}@keyframes error-appear{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
 @media(max-width:480px){.error-code{max-width:320px}p{font-size:14px}}
 </style>
</head>
<body>
 <main class="error-screen"><section class="error-content" aria-labelledby="error-one-title">
  <svg class="error-code" viewBox="0 0 800 300" aria-hidden="true" focusable="false"><defs><linearGradient id="error-code-gradient" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#245bdb" stop-opacity=".25"/><stop offset="1" stop-color="#245bdb" stop-opacity=".06"/></linearGradient></defs><text x="49%" y="54%" text-anchor="middle" dominant-baseline="middle">{$code}</text></svg>
  <h1 id="error-one-title"><span style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip-path:inset(50%);white-space:nowrap">Error {$code}: </span>{$title}</h1>
  <p>{$description}</p>
  <a class="error-home" href="{$home}"><span>Go Back Home</span><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m12 3 10 8h-3v10h-5v-6h-4v6H5V11H2Z"/></svg></a>
 </section></main>
</body></html>
HTML;
    exit;
}
