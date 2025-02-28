<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini AI Prompter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Google Gemini AI Prompter</h2>
        <div class="card p-4">
            <div class="form-group">
                <label>Enter a Topic or Keywords:</label>
                <input type="text" id="user_input" class="form-control" placeholder="E.g., Write a story about space travel">
            </div>
            <button class="btn btn-primary btn-block" onclick="generatePrompt()">Generate</button>
            <div class="mt-3">
                <h5>AI Response:</h5>
                <div id="response" class="alert alert-info">Waiting for input...</div>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>
