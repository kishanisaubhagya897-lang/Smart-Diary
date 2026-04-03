// Modal controls
document.addEventListener("DOMContentLoaded", () => {
    const usernameModal = document.getElementById("usernameModal");
    const emailModal = document.getElementById("emailModal");
    const passwordModal = document.getElementById("passwordModal");
  
    document.getElementById("usernameChange").addEventListener("click", () => {
      usernameModal.style.display = "flex";
    });
  
    document.getElementById("emailChange").addEventListener("click", () => {
      emailModal.style.display = "flex";
    });
  
    document.getElementById("passwordChange").addEventListener("click", () => {
      passwordModal.style.display = "flex";
    });
  
    document.querySelectorAll(".close").forEach((close) => {
      close.addEventListener("click", () => {
        usernameModal.style.display = "none";
        emailModal.style.display = "none";
        passwordModal.style.display = "none";
      });
    });
  
    // Close modal if clicked outside content
    window.addEventListener("click", (e) => {
      if (e.target === usernameModal) usernameModal.style.display = "none";
      if (e.target === emailModal) emailModal.style.display = "none";
      if (e.target === passwordModal) passwordModal.style.display = "none";
    });
  
    // Toast Notification
    const toast = document.getElementById("toast");
    if (toast) {
      toast.style.display = "flex";
      setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => (toast.style.display = "none"), 500);
      }, 3000);
    }
  });
  