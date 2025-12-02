function loadLogs() {
  const role = document.getElementById("roleFilter").value;
  const search = document.getElementById("searchInput").value.trim();

  // Build the query string dynamically
  let url = "admin.dbf/search_logs.php?";
  const params = [];

  if (search.length > 0) {
    params.push("search=" + encodeURIComponent(search));
  }

  if (role !== "all") {
    params.push("role=" + encodeURIComponent(role));
  }

  url += params.join("&");

  fetch(url)
    .then((res) => res.json())
    .then((data) => {
      let rows = "";

      if (data.length === 0) {
        rows = `<tr><td colspan="8" class="no-record">No activity logs found.</td></tr>`;
      } else {
        data.forEach((row) => {
          rows += `
                        <tr class="history-row">
                            <td class="id-col">${row.id}</td>
                            <td class="id-col">${row.user_id}</td>
                            <td class="rows">${row.username}</td>
                            <td class="rows">${row.role}</td>
                            <td class="rows">${row.action_description}</td>
                            <td>${row.date_only}</td>
                            <td>${row.time_12h}</td>
                            <td class="rows">${row.status}</td>
                        </tr>`;
        });
      }

      document.getElementById("logsTableBody").innerHTML = rows;
    });
}

document.getElementById("roleFilter").addEventListener("change", loadLogs);
document.getElementById("searchInput").addEventListener("input", loadLogs);

// Load default
loadLogs();

let logsData = []; // Store fetched logs globally

// Fetch logs from server
function fetchLogs(role, search) {
  let url = "admin.dbf/search_logs.php?";
  const params = [];
  if (search) params.push("search=" + encodeURIComponent(search));
  if (role && role !== "all") params.push("role=" + encodeURIComponent(role));
  url += params.join("&");

  return fetch(url).then((res) => res.json());
}

// Render logs table
function renderLogsTable(logs) {
  logsData = logs; // Save globally
  const tbody = document.getElementById("logsTableBody");

  if (logs.length === 0) {
    tbody.innerHTML = `<tr><td colspan="8" class="no-record">No activity logs found.</td></tr>`;
    return;
  }

  tbody.innerHTML = logs
    .map(
      (log, index) => `
        <tr class="history-row" data-log-index="${index}" style="cursor:pointer;">
            <td class="id-col">${log.id}</td>
            <td class="id-col">${log.user_id}</td>
            <td class="rows">${log.username}</td>
            <td class="rows">${log.role}</td>
            <td class="rows">${log.action_description}</td>
            <td>${log.date_only}</td>
            <td>${log.time_12h}</td>
            <td class="rows">${log.status}</td>
        </tr>
    `
    )
    .join("");

  attachRowClickEvents();
}

// Attach click events to rows
function attachRowClickEvents() {
  document.querySelectorAll(".history-row").forEach((row) => {
    row.addEventListener("click", () => {
      const index = row.dataset.logIndex;
      showLogModal(logsData[index]);
    });
  });
}

// Show modal with log details
function showLogModal(log) {
  if (!log) return;

  document.getElementById("modalLogID").innerText = log.id;
  document.getElementById("modalUserID").innerText = log.user_id;
  document.getElementById("modalUsername").innerText = log.username;
  document.getElementById("modalRole").innerText = log.role;
  document.getElementById("modalActionType").innerText =
    log.action_type || "N/A";
  document.getElementById("modalActionDescription").innerText =
    log.action_description;
  document.getElementById("modalDate").innerText = log.date_only;
  document.getElementById("modalTime").innerText = log.time_12h;
  document.getElementById("modalStatus").innerText = log.status;

  document.getElementById("logModal").style.display = "block";
  document.body.style.overflow = "hidden";
}

// Close modal
function closeModal() {
  document.getElementById("logModal").style.display = "none";
}

// Load logs with current filters
function loadLogs() {
  const role = document.getElementById("roleFilter").value;
  const search = document.getElementById("searchInput").value.trim();

  fetchLogs(role, search)
    .then(renderLogsTable)
    .catch((err) => console.error("Error fetching logs:", err));
}

// Event listeners
document.getElementById("roleFilter").addEventListener("change", loadLogs);
document.getElementById("searchInput").addEventListener("input", loadLogs);
document.querySelector(".modal .close").addEventListener("click", closeModal);
window.addEventListener("click", (e) => {
  if (e.target.id === "logModal") closeModal();
});

// Initial load
loadLogs();
