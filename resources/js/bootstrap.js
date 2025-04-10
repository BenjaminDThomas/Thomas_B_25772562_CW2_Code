document.addEventListener("DOMContentLoaded", function () {
    let questionIndex = 1; // Start index from question 1
    const addButton = document.getElementById("add-question");
    const questionsContainer = document.getElementById("questions-container");

    if (!addButton || !questionsContainer) return;

    // Add new questions
    addButton.addEventListener("click", function () {
        const newQuestionDiv = document.createElement("div");
        newQuestionDiv.classList.add("form-group", "question", "mt-3");
        newQuestionDiv.id = `question-${questionIndex}`;

        newQuestionDiv.innerHTML = `
            <h4>Question ${questionIndex + 1}</h4>

            <div class="form-group">
                <label for="questions[${questionIndex}][text]">Question Text:</label>
                <input type="text" class="form-control" name="questions[${questionIndex}][text]" placeholder="Enter Question" required>
            </div>

            <div class="form-group">
                <label for="questions[${questionIndex}][type]">Type:</label>
                <select class="form-control" name="questions[${questionIndex}][type]" required>
                    <option value="quantitative">Quantitative (Rating 1-6)</option>
                    <option value="qualitative">Qualitative (Text Answer)</option>
                </select>
            </div>

            <div class="options" id="options-${questionIndex}" style="display:none;">
                <label>Options (for Quantitative questions, 1 to 6)</label>
                <div class="rating-options">
                    ${[...Array(6).keys()].map(i => `
                        <label>
                            <input type="radio" name="questions[${questionIndex}][options][]" value="${i + 1}">
                            ${i + 1}: ${i === 0 ? "Lowest" : i === 5 ? "Highest" : ""}
                        </label><br>
                    `).join('')}
                </div>
            </div>
        `;

        questionsContainer.appendChild(newQuestionDiv);
        questionIndex++; // Increment for new questions

        newQuestionDiv.querySelector('input').focus();
    });
});
