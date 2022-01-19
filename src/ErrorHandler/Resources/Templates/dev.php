<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <title>Fatal Error</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.7.1/dist/css/uikit.min.css" />

    <link rel="stylesheet" href="/public/assets/css/normalize.css">
    <link rel="stylesheet" href="/public/assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200&display=swap" rel="stylesheet"/>
    <style>
    body {font-family: "Courier New" color: #000000;}
    pre {
        color: crimson;
        background-color: #f8f8f8;
        padding: 5px;
        /*margin: 25px;*/
        font-size: 98%;
    }
    .error-line {
        color: gold;
    }
    .error-line:after {content:'!';}
    </style>
</head>

<body>
<section>
    <div class="uk-container uk-container-small uk-light uk-background-secondary uk-margin-medium-top uk-margin-medium-bottom uk-padding">
        <article class="uk-article">
            <?php
            $errfile = $exception->getFile();
            $errline = $exception->getLine();
            $errcode = $exception->getCode();
            $errstr = $exception->getMessage();
            $errTrace = $exception->getTrace();
            $errDebugBacktrace = debug_backtrace();
            $errType = get_class($exception);
            ?>

            <h1 class="uk-article-title"><span uk-icon="icon: warning; ratio: 3.5"></span> Application Error</h1>
            <p class="ion-21">The application encountered the following error below.</p>

            <h2 class="uk-heading-line"><span>Error Details</span></h2>
            <ul class="uk-list uk-list-collapse">
                <li><strong>Uncaught Exception:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo $errType ?? null; ?></span></li>
                <li><strong>Code:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo $errcode ?? null; ?></span></li>
                <li><strong>File:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo $errfile ?? null; ?></span></li>
                <?php if (isset($snippet)) : ?>
                <li><strong>Error:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo $snippet ?? null; ?></span></li>
                <?php endif; ?>
                <?php if (isset($srcCode)) : ?>
                <li><strong>Source:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo $srcCode ?? null; ?></span></li>
                <?php endif; ?>
                <li><strong>Line:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo $errline ?? null; ?></span></li>
                <li><strong>Message:</strong> <span class="uk-text-danger uk-text-bolder"><?php echo htmlentities($errstr) ?? null; ?></span></li>

            </ul>
            <ul class="uk-subnav uk-subnav-pill" uk-switcher>
                <li><a class="uk-text-capitalize uk-text-bolder" href="#">StackTrace <span>(<?php echo count($errTrace) > 0 ? count($errTrace) : 0; ?>)</span></a></li>
                <li><a class="uk-text-capitalize uk-text-bolder" href="#"><?php echo $errType ?? null; ?></a></li>
                <li><a class="uk-text-capitalize uk-text-bolder" href="#">Debug Backtrace <span>(<?php echo count($errDebugBacktrace) > 0 ? count($errDebugBacktrace) : 0; ?>)</span></a></li>
            </ul>

            <ul class="uk-switcher uk-margin">
                <li>
                    <pre class="uk-text-bolder uk-dark uk-background-muted"><?php echo "\n" . $exception->getTraceAsString(); ?></pre>
                    <a class="uk-text-bolder" href="#" onClick="window.history.go(-1)">Go Back</a>
                </li>
                <li>
                    <pre class="uk-dark uk-background-muted">
                       <?php
                       $out = '';
                       foreach($stacktrace as $strace) {
                           if ($strace['file'] == $errfile) {
                               $out = $strace['code'];
                           }
                       }
                       echo $out;
                       ?>
                    </pre>

                </li>
                <li>
                    <pre class="uk-text-bolder uk-dark uk-background-muted">
                    <?php
                    echo debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                    ?>
                    </pre>
                </li>
            </ul>
        </article>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/uikit@3.7.1/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.7.1/dist/js/uikit-icons.min.js"></script>

</body>

</html>

