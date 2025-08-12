document.addEventListener('DOMContentLoaded', () => {
  // Find all elements that depend on a controller
  const dependentFields = document.querySelectorAll<HTMLElement>('[data-depends-on]');

  dependentFields.forEach(field => {
    const dependsOnId = field.getAttribute('data-depends-on');
    if (!dependsOnId) return;

    const controller = document.getElementById(dependsOnId) as HTMLInputElement | null;
    if (!controller) return;

    const toggleField = () => {
      if (controller.checked) {
        field.classList.remove('hidden');
      } else {
        field.classList.add('hidden');
      }
    };

    controller.addEventListener('change', toggleField);
    toggleField(); // Set initial state
  });
});