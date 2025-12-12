document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const restoreType = urlParams.get("restore");
  const message = urlParams.get("msg");

  if (!restoreType || !message) return;

  const modal = document.getElementById("RestoreSuccessModal");
  const closeBtn = modal.querySelector(".closeRestoreModal");
  const okBtn = modal.querySelector(".closeRestoreBtn");

  document.getElementById("restoreModalMessage").textContent =
    decodeURIComponent(message);

  modal.classList.add("show"); // use class for smooth transition

  function closeModal() {
    modal.classList.remove("show");
    const baseUrl = window.location.href.split("?")[0];
    window.history.replaceState({}, "", baseUrl);
  }

  closeBtn.addEventListener("click", closeModal);
  okBtn.addEventListener("click", closeModal);

  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const deleteType = urlParams.get("delete");
  const message = urlParams.get("msg");

  if (!deleteType || !message) return;

  const modal = document.getElementById("deleteSuccessModal");
  const modalMessage = document.getElementById("deleteModalMessage");
  const closeBtn = modal.querySelector(".closeDeleteModal");
  const okBtn = modal.querySelector(".closeModalBtn");

  modalMessage.textContent = decodeURIComponent(message);
  modal.style.display = "flex";

  function closeModal() {
    modal.style.display = "none";
    const baseUrl = window.location.href.split("?")[0];
    window.history.replaceState({}, "", baseUrl);
  }

  closeBtn.addEventListener("click", closeModal);
  okBtn.addEventListener("click", closeModal);

  modal.addEventListener("click", function (e) {
    if (e.target === modal) closeModal();
  });
});
