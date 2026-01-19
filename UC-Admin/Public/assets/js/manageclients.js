document.querySelectorAll(".tab").forEach((tab) => {
  tab.addEventListener("click", function () {
    document
      .querySelectorAll(".tab")
      .forEach((t) => t.classList.remove("active"));
    document
      .querySelectorAll(".tab-content")
      .forEach((c) => (c.style.display = "none"));

    this.classList.add("active");
    const contentId = this.getAttribute("data-target");
    const targetContent = document.getElementById(contentId);
    if (targetContent) {
      targetContent.style.display = "block";
    }

    const dropdownCon = document.querySelector(".drop-down-con");
    if (dropdownCon) {
      if (contentId === "personnel-content") {
        dropdownCon.style.display = "none";
      } else {
        dropdownCon.style.display = "flex";
      }
    }
  });
});

document
  .getElementById("clientTypeDropdown")
  .addEventListener("change", function () {
    const selectedContent = this.value;

    // Hide all tab content
    document.querySelectorAll(".tab-content").forEach((content) => {
      content.style.display = "none";
    });

    const targetContent = document.getElementById(selectedContent);
    if (targetContent) {
      targetContent.style.display = "block";

      // Ensure the table container maintains scroll functionality
      const tableContainer = targetContent.querySelector(".table-div");
      if (tableContainer) {
        tableContainer.style.maxHeight = "500px";
        tableContainer.style.overflowY = "auto";
      }
    }
  });

function addDoubleClickToRows() {
  document.querySelectorAll(".client-row").forEach((row) => {
    row.addEventListener("dblclick", function () {
      // Find the eye icon link in this row
      const eyeIconLink = this.querySelector('a[title="View Profile"]');
      if (eyeIconLink) {
        window.location.href = eyeIconLink.href;
      }
    });

    // Optional: Add hover effect to indicate clickability
    row.style.cursor = "pointer";
  });
}

// Initialize double-click on page load
document.addEventListener("DOMContentLoaded", function () {
  addDoubleClickToRows();

  // Re-initialize after search results are loaded
  const originalLoadFilteredData = loadFilteredData;
  loadFilteredData = function (tabId, clientType, searchId) {
    return originalLoadFilteredData(tabId, clientType, searchId).then(() => {
      addDoubleClickToRows();
    });
  };
});

// Also update the existing loadFilteredData function to maintain the promise chain
const originalLoadFilteredData = loadFilteredData;
loadFilteredData = function (tabId, clientType, searchId) {
  return originalLoadFilteredData(tabId, clientType, searchId).then(() => {
    addDoubleClickToRows();
  });
};

function loadFilteredData(tabId, clientType, searchId) {
  return fetch(
    `manageclients.dbf/get_user.php?client_type=${clientType}&id_filter=${encodeURIComponent(
      searchId,
    )}`,
  )
    .then((response) => response.text())
    .then((html) => {
      // Replace "View" buttons with eye icons in the returned HTML
      const updatedHtml = html.replace(
        /<a href="ClientProfile\.php\?id=([^"]+)" class="btn btn-primary btn-sm">View<\/a>/g,
        '<a href="ClientProfile.php?id=$1" title="View Profile"><i class="fas fa-eye eye-icon" style="color: #000; font-size: 18px;"></i></a>',
      );

      const tbody = document.querySelector(`#${tabId} tbody`);
      if (tbody) {
        tbody.innerHTML = updatedHtml;

        // Ensure scroll is maintained after loading new data
        const tableContainer = document.querySelector(`#${tabId} .table-div`);
        if (tableContainer) {
          tableContainer.style.maxHeight = "500px";
          tableContainer.style.overflowY = "auto";
        }
      }
    });
}

// Initialize scroll on page load
document.addEventListener("DOMContentLoaded", function () {
  // Ensure all table containers have scroll enabled
  document.querySelectorAll(".table-div").forEach((container) => {
    container.style.maxHeight = "500px";
    container.style.overflowY = "auto";
  });

  const urlParams = new URLSearchParams(window.location.search);
  const idFilter = urlParams.get("id_filter");

  if (idFilter) {
    searchInput.value = idFilter;
  }

  // Set default tab to students
  document.getElementById("students-content").style.display = "block";
});

function initializeEmailValidation() {
  const emailInput = document.getElementById("emailInput");
  const emailError = document.getElementById("emailError");
  const saveButton = document.getElementById("saveButton");
  const form = document.getElementById("addPatientForm");

  if (emailInput) {
    // Real-time email validation on input change
    emailInput.addEventListener("blur", function () {
      validateEmail(this.value);
    });

    // Clear error when user starts typing again
    emailInput.addEventListener("input", function () {
      if (emailError.style.display !== "none") {
        emailError.style.display = "none";
        emailInput.style.borderColor = "#ddd";
        saveButton.disabled = false;
        saveButton.style.backgroundColor = "#28a745";
      }
    });
  }

  // Form submission validation
  if (form) {
    form.addEventListener("submit", function (e) {
      const email = emailInput.value.trim();
      if (!validateEmailOnSubmit(email)) {
        e.preventDefault(); // Prevent form submission
      }
    });
  }
}

// Real-time email validation
function validateEmail(email) {
  if (email === "") return;

  fetch("manageclients.dbf/check_email.php?email=" + encodeURIComponent(email))
    .then((response) => response.json())
    .then((data) => {
      const emailError = document.getElementById("emailError");
      const emailInput = document.getElementById("emailInput");
      const saveButton = document.getElementById("saveButton");

      if (data.exists) {
        emailError.style.display = "block";
        emailInput.style.borderColor = "#e74c3c";
        saveButton.disabled = true;
        saveButton.style.backgroundColor = "#95a5a6";
      } else {
        emailError.style.display = "none";
        emailInput.style.borderColor = "#ddd";
        saveButton.disabled = false;
        saveButton.style.backgroundColor = "#28a745";
      }
    })
    .catch((error) => {
      console.error("Error checking email:", error);
    });
}

function validateEmailOnSubmit(email) {
  if (email === "") return true;

  // Simple synchronous validation for final check
  const emailError = document.getElementById("emailError");
  if (emailError.style.display === "block") {
    emailInput.scrollIntoView({ behavior: "smooth", block: "center" });
    emailInput.focus();
    return false;
  }
  return true;
}

function openAddPatientModal() {
  document.getElementById("addPatientModal").style.display = "block";
  setTimeout(initializeEmailValidation, 100);

  const emailError = document.getElementById("emailError");
  const emailInput = document.getElementById("emailInput");
  const saveButton = document.getElementById("saveButton");

  if (emailError) emailError.style.display = "none";
  if (emailInput) emailInput.style.borderColor = "#ddd";
  if (saveButton) {
    saveButton.disabled = false;
    saveButton.style.backgroundColor = "#28a745";
  }
}

/*
function filterTabledep() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const department = document.getElementById("department").value.toLowerCase();

    const visibleTab = document.querySelector('.tab-content[style*="display: block"]');
    const rows = visibleTab.querySelectorAll(".client-row");

    rows.forEach(row => {
        const nameCell = row.querySelector(".searchable-name").textContent.toLowerCase();
        const departmentCell = row.querySelector("td:nth-child(5)")?.textContent.toLowerCase(); // 5th column = department

        const matchesName = nameCell.includes(input);
        const matchesDepartment = department === "" || (departmentCell && departmentCell.includes(department));

        if (matchesName && matchesDepartment) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

async function filterTableById() {
    const searchValue = document.getElementById('searchInput').value.trim();
    
    if (searchValue === '') {
        document.querySelectorAll('.clientTableBody tr.client-row').forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    try {
        const response = await fetch(`../../manageclients.dbf/get_user.php?searchById=${encodeURIComponent(searchValue)}`);
        const results = await response.json();
        
        const tableBody = document.querySelector('.clientTableBody');
        tableBody.innerHTML = '';
        
        if (results.length > 0) {
            const client = results[0];
            const row = document.createElement('tr');
            row.className = 'client-row';
            row.innerHTML = `
                <td class="searchable-id">${client.ClientID}</td>
                <td><img src="${client.profilePicturePath || 'default.jpg'}" class="profile-pic"></td>
                <td>${client.FullName}</td>
                <td>${client.Email}</td>
                <td>${client.Course}</td>
                <td>${client.Department}</td>
                <td>${client.ClientType}</td>
                <td class="action-buttons">
                    <button class="btn btn-primary btn-sm view-btn" data-id="${client.ClientID}">View</button>
                    <button class="btn btn-info btn-sm edit-btn" data-id="${client.ClientID}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="${client.ClientID}">Delete</button>
                </td>
            `;
            tableBody.appendChild(row);
        } else {
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center">No matching ID found</td></tr>';
        }
    } catch (error) {
        console.error('Error searching:', error);
    }
}
*/
//==============================================================================
let selectedClientId = null;

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("deleteClientModal");
  const trashBtn = document.getElementById("trashBtn");
  const permanentBtn = document.getElementById("permanentBtn");
  const cancelBtn = document.getElementById("cancelBtn");

  // ✅ Row click → profile
  document.querySelectorAll(".client-row").forEach((row) => {
    row.addEventListener("click", (e) => {
      // Ignore clicks inside action buttons
      if (e.target.closest(".action-buttons")) return;

      window.location.href = row.dataset.href;
    });
  });

  // ✅ Delete button → show modal
  document.querySelectorAll(".delete-client-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation(); // prevent row click

      selectedClientId = btn.dataset.clientId;
      modal.style.display = "flex";
    });
  });

  // ✅ Move to Trash
  trashBtn.addEventListener("click", () => {
    window.location.href = `manageclients.dbf/delete_client.php?id=${selectedClientId}&action=trash`;
  });

  // ✅ Permanent Delete
  permanentBtn.addEventListener("click", () => {
    if (!confirm("This action is irreversible. Continue?")) return;
    window.location.href = `manageclients.dbf/delete_client.php?id=${selectedClientId}&action=permanent`;
  });

  // ✅ Cancel Modal
  cancelBtn.addEventListener("click", () => {
    modal.style.display = "none";
    selectedClientId = null;
  });
});

//==============================================================================
//This part handle the rows in users table, it prevent auto directing to client/patients profile
//when you click the delete icon
document.querySelectorAll(".row-delete-btn").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.stopPropagation(); // ⛔ Prevent row redirect
    e.preventDefault(); // ⛔ Prevent any default behavior

    selectedUserId = this.dataset.id;
    selectedUrl = this.dataset.url;

    document.getElementById("deleteModal").style.display = "flex";
  });
});
//===============================================================================
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const resetBtn = document.getElementById("resetSearch");

  const tabConfig = {
    AllPatients: "All",
    Students: "Student",
    Freshman: "Freshman",
    Faculty: "Faculty",
    Personnel: "Personnel",
    NewPersonnel: "NewPersonnel",
  };

  let debounceTimer;

  if (searchInput) {
    searchInput.addEventListener("input", function () {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        const searchId = searchInput.value.trim();
        updateUrl(searchId);
        loadAllTabs(searchId);
      }, 300);
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener("click", function () {
      searchInput.value = "";
      updateUrl("");
      loadAllTabs("");
    });
  }

  function loadAllTabs(searchId) {
    Object.entries(tabConfig).forEach(([tabId, clientType]) => {
      loadFilteredData(tabId, clientType, searchId);
    });
  }

  function loadFilteredData(tabId, clientType, searchId) {
    fetch(
      `manageclients.dbf/get_user.php?client_type=${encodeURIComponent(
        clientType,
      )}&id_filter=${encodeURIComponent(searchId)}`,
    )
      .then((response) => response.text())
      .then((html) => {
        const tbody = document.querySelector(`#${tabId} tbody`);
        if (tbody) {
          tbody.innerHTML = html;
          attachRowClickEvents(`#${tabId} tbody .client-row`);
        }
      })
      .catch((err) => console.error("Error loading data:", err));
  }

  function updateUrl(searchId) {
    const url = new URL(window.location);
    if (!searchId) {
      url.searchParams.delete("id_filter");
    } else {
      url.searchParams.set("id_filter", searchId);
    }
    window.history.replaceState({}, "", url);
  }

  function attachRowClickEvents(selector) {
    document.querySelectorAll(selector).forEach((row) => {
      row.addEventListener("click", function (e) {
        if (e.target.closest(".row-delete-btn")) return;
        if (this.dataset.href) window.location.href = this.dataset.href;
      });
    });
  }

  const urlParams = new URLSearchParams(window.location.search);
  const idFilter = urlParams.get("id_filter");

  if (idFilter && searchInput) {
    searchInput.value = idFilter;
    loadAllTabs(idFilter);
  } else {
    loadAllTabs("");
  }

  const activeTab = sessionStorage.getItem("activeTab");
  if (activeTab) {
    const tab = document.querySelector(
      `.nav-tabs .nav-link[data-bs-target="${activeTab}"]`,
    );
    if (tab) tab.click();
  }
});

//===================================================================================
let selectedUserId = null;
let selectedUrl = null;

document.addEventListener("click", function (e) {
  const btn = e.target.closest(".row-delete-btn");
  if (!btn) return;

  e.preventDefault();
  e.stopPropagation();

  selectedUserId = btn.dataset.id;
  selectedUrl = btn.dataset.url;
  document.getElementById("deleteModal").style.display = "flex";
});

// Modal buttons
document.getElementById("tempDeleteBtn").addEventListener("click", function () {
  window.location.href = selectedUrl + "?action=archive&id=" + selectedUserId;
});
document.getElementById("permDeleteBtn").addEventListener("click", function () {
  window.location.href = selectedUrl + "?action=permanent&id=" + selectedUserId;
});
document.getElementById("closeModal").addEventListener("click", function () {
  document.getElementById("deleteModal").style.display = "none";
});
//=========================================================================
//this is success delete modal
document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const deleteType = urlParams.get("delete"); // must match PHP query
  const message = urlParams.get("msg");

  if (!deleteType || !message) return;

  const modal = document.getElementById("deleteSuccessModal");
  const closeBtn = modal.querySelector(".closeDeleteModal");
  const okBtn = modal.querySelector(".closeModalBtn");

  document.getElementById("deleteModalMessage").textContent =
    decodeURIComponent(message);
  modal.classList.add("show");

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
  let staffToDeleteId = null;

  const confirmationModal = document.getElementById("confirmationModal");
  const confirmationName = document.getElementById("confirmationName");
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

  // Open confirmation modal
  document.querySelectorAll(".row-delete-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      staffToDeleteId = btn.dataset.id;
      const row = btn.closest("tr");
      const name = row.querySelector(".searchable-name").textContent;
      confirmationName.textContent = name;
      confirmationModal.style.display = "block";
    });
  });

  // Confirm delete
  confirmDeleteBtn.addEventListener("click", function () {
    if (!staffToDeleteId) return;

    fetch("manageclients.dbf/delete_staff.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + encodeURIComponent(staffToDeleteId),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          // Remove row from table
          document.querySelectorAll(".client-row").forEach((r) => {
            const idCell = r.querySelector(".searchable-id");
            if (idCell && idCell.textContent.trim() == staffToDeleteId) {
              r.remove();
            }
          });
          alert(data.message);
        } else {
          alert("Delete failed: " + data.message);
        }
        staffToDeleteId = null;
        closeConfirmationModal();
      })
      .catch((err) => {
        alert("Delete failed: " + err);
        staffToDeleteId = null;
        closeConfirmationModal();
      });
  });

  function closeConfirmationModal() {
    confirmationModal.style.display = "none";
  }

  window.closeConfirmationModal = closeConfirmationModal;
});
