<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>Base8 Starter</title>

    <style>

        body {

            margin: 40px;
            background: #f5f5f5;
            color: #222;
            font: 15px Consolas, Monaco, monospace;

        }

        .card {

            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            padding: 24px;

        }

        h1 {

            margin: 0 0 8px;
            font-size: 28px;

        }

        .subtitle {

            margin: 0 0 24px;
            color: #666;
            line-height: 1.6;

        }

        table {

            width: 100%;
            border-collapse: collapse;

        }

        td {

            padding: 8px 0;

        }

        td:first-child {

            width: 180px;
            font-weight: bold;

        }

        h2 {

            margin: 32px 0 12px;
            font-size: 18px;

        }

        ul {

            margin: 0;
            padding-left: 20px;

        }

        li {

            margin: 8px 0;

        }

        code {

            background: #f5f5f5;
            padding: 2px 6px;

        }

        hr {

            margin: 32px 0;
            border: 0;
            border-top: 1px solid #ddd;

        }

        .success {

            margin-top: 24px;
            color: #198754;
            font-weight: bold;

        }

        .footer {

            color: #666;
            font-size: 13px;

        }

        .footer a {

            color: inherit;
            text-decoration: none;

        }

        .footer a:hover {

            text-decoration: underline;

        }

    </style>

</head>

<body>

<div class="card">

    <h1>Welcome to Base8 Starter</h1>

    <p class="subtitle">

        <strong>Lightweight PHP Framework</strong><br>

        One build. One framework file. One starter package.

    </p>

    <table>

        <tr>

            <td>Application</td>

            <td>Base8 Starter</td>

        </tr>

        <tr>

            <td>Framework</td>

            <td>Base8</td>

        </tr>

        <tr>

            <td>PHP Version</td>

            <td><?= PHP_VERSION ?></td>

        </tr>

        <tr>

            <td>Status</td>

            <td>Ready</td>

        </tr>

    </table>

    <p class="success">

        ✔ Your Base8 Starter application is ready.

    </p>

    <h2>Next Steps</h2>

    <ul>

        <li>Create your first module in <code>app/modules</code>.</li>

        <li>Create your first view in <code>app/views</code>.</li>

        <li>Explore the documentation in the <code>docs</code> directory.</li>

    </ul>

    <hr>

    <p class="footer">

        Base8 • MIT License •

        <a href="https://ollywer.com" target="_blank">

            ollywer.com

        </a>

    </p>

</div>

</body>

</html>