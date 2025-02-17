document.addEventListener("DOMContentLoaded", function () {
    const carousels = document.querySelectorAll(".carousel");

    carousels.forEach(carousel => {
        const leftBtn = carousel.parentElement.querySelector(".left-btn");
        const rightBtn = carousel.parentElement.querySelector(".right-btn");

        // Vérification si les éléments existent
        if (carousel && leftBtn && rightBtn) {
            leftBtn.addEventListener("click", () => {
                carousel.scrollBy({ left: -300, behavior: "smooth" }); 
            });

            rightBtn.addEventListener("click", () => {
                carousel.scrollBy({ left: 300, behavior: "smooth" }); 
            });
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const testimonialItems = document.querySelectorAll(".testimonial-item");
    const dots = document.querySelectorAll(".dot");
    const itemsPerPage = 3;
    let currentPage = 0;

    // Afficher les témoignages pour la page actuelle
    function showTestimonials(page) {
        const startIndex = page * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        testimonialItems.forEach((item, index) => {
            item.classList.remove("active");
            if (index >= startIndex && index < endIndex) {
                item.classList.add("active");
            }
        });

        dots.forEach((dot, index) => {
            dot.classList.remove("active");
            if (index === page) {
                dot.classList.add("active");  // Mettre le point actif
            }
        });
    }

    // Cliquer sur un point pour naviguer vers le groupe de témoignages correspondant
    dots.forEach(dot => {
        dot.addEventListener("click", function () {
            currentPage = parseInt(this.getAttribute("data-index"));
            showTestimonials(currentPage);
        });
    });

    // Afficher le premier groupe de témoignages par défaut
    showTestimonials(currentPage);
});
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-course-btn");
    const modalTitle = document.getElementById("addCourseModalLabel");
    const courseTitleInput = document.getElementById("courseTitle");
    const courseDescriptionInput = document.getElementById("courseDescription");
    const courseIdInput = document.getElementById("courseId");
    const submitButton = document.getElementById("modalSubmitButton");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const courseId = this.getAttribute("data-id");
            const courseTitle = this.getAttribute("data-title");
            const courseDescription = this.getAttribute("data-description");

            // Modifier les valeurs des champs
            courseTitleInput.value = courseTitle;
            courseDescriptionInput.value = courseDescription;
            courseIdInput.value = courseId;

            // Changer le titre et le bouton
            modalTitle.textContent = "Modifier un cours";
            submitButton.textContent = "Modifier";
        });
    });

    // Remettre le formulaire à zéro quand on clique sur "Ajouter un cours"
    document.querySelector("[data-bs-target='#addCourseModal']").addEventListener("click", function () {
        modalTitle.textContent = "Ajouter un cours";
        courseTitleInput.value = "";
        courseDescriptionInput.value = "";
        courseIdInput.value = "";
        submitButton.textContent = "Ajouter";
    });
});