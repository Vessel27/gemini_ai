function generatePrompt() {
    var userInput = $("#user_input").val().trim();
    if (userInput === "") {
        return;
    }

    // Append user message
    $("#chatbox").append(`<div class="message user">${userInput}</div>`);
    $("#user_input").val(""); // Clear input field
    $("#chatbox").scrollTop($("#chatbox")[0].scrollHeight); // Auto-scroll

    $.ajax({
        url: "gemini_prompter.php",
        type: "POST",
        data: { user_input: userInput },
        dataType: "json",
        success: function(response) {
            console.log("API Response:", response);
            let botMessage = response.success ? response.message : "Error: " + response.message;
            
            // Append bot response
            $("#chatbox").append(`<div class="message bot">${botMessage}</div>`);
            $("#chatbox").scrollTop($("#chatbox")[0].scrollHeight);
        },
        error: function(xhr, status, error) {
            console.log("Full Error:", xhr.responseText);
            $("#chatbox").append(`<div class="message bot" style="color: red;">Error: ${error}</div>`);
            $("#chatbox").scrollTop($("#chatbox")[0].scrollHeight);
        }
    });
}