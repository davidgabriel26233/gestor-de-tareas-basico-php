document.addEventListener('DOMContentLoaded', () => {
    const buttonsToggleComplete = document.querySelectorAll('.toggle-complete');

    const toggleCompleteTask = async (event) => {
        const taskDiv = event.target.parentElement.parentElement.parentElement.parentElement.parentElement;
        const url = new URL(event.target.parentElement.nextElementSibling.firstElementChild.href);
        const taskId = url.searchParams.get('id');

        const response = await fetch('./complete-task.php', {
            method: 'PUT',
            body: JSON.stringify({ taskId })
        });

        if (!response.ok) {
            alert('Ocurrio un error');
            return;
        }
        
        const { complete } = await response.json();

        if (complete) {
            taskDiv.classList.remove('border-warning');
            taskDiv.classList.add('border-success');
            return;
        }

        taskDiv.classList.remove('border-success');
        taskDiv.classList.add('border-warning');
    }

    buttonsToggleComplete.forEach(button => button.addEventListener('click', toggleCompleteTask));
});