<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dynamic Question Form</title>
</head>
<body>
<h2>Dynamic Question Form</h2>
<form id="question-form" action="submit_form.php" method="post">
  <div id="questions-container">
    <!-- Questions will be dynamically added here -->
  </div>
  <button type="button" onclick="addQuestion()">Add Question</button>
  <button type="submit">Submit</button>
</form>

<script>
  let questionCounter = 0;

  function addQuestion() {
    questionCounter++;

    const questionContainer = document.getElementById('questions-container');

    const questionDiv = document.createElement('div');
    questionDiv.innerHTML = `
      <label for="question${questionCounter}">Question ${questionCounter}:</label>
      <input type="text" id="question${questionCounter}" name="questionsData[${questionCounter}][question]" required>
      <div id="options-container-${questionCounter}">
        <input type="text" name="questionsData[${questionCounter}][options][]" placeholder="Option 1" required>
        <input type="text" name="questionsData[${questionCounter}][options][]" placeholder="Option 2" required>
        <!-- Add more options dynamically -->
      </div>
      <button type="button" onclick="addOption(${questionCounter})">Add Option</button>
    `;
    questionContainer.appendChild(questionDiv);
  }

  function addOption(questionNumber) {
    const optionsContainer = document.getElementById(`options-container-${questionNumber}`);
    const optionCount = optionsContainer.children.length + 1;

    const optionInput = document.createElement('input');
    optionInput.type = 'text';
    optionInput.name = `questionsData[${questionNumber}][options][]`;
    optionInput.placeholder = `Option ${optionCount}`;
    optionInput.required = true;

    optionsContainer.appendChild(optionInput);
  }
</script>
</body>
</html>
