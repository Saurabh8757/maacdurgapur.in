/*
FCQ
JS
*/

document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.fcq-question');

    questions.forEach(question => {
        question.addEventListener('click', function() {
            const currentItem = this.parentElement;
            const isActive = currentItem.classList.contains('active');

            // Optional: Close all other items if you only want one open at a time
            const allItems = document.querySelectorAll('.fcq-item');
            allItems.forEach(item => {
                item.classList.remove('active');
            });

            // Toggle current item
            if (!isActive) {
                currentItem.classList.add('active');
            }
        });
    });
});
