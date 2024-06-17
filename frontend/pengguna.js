// Add JS here
document.addEventListener("DOMContentLoaded", () => {
	console.log("Page Pengguna")
	getUsers()
});
const token = localStorage.getItem("token");
let isLoading = false;

document.getElementById("btn-refresh").addEventListener("click", (e) => {
	// Cek apakah sedang memuat data
	if (isLoading === false) {
		getUsers();
	}
})

async function editUser(users_id) {
	window.location = `pengguna-edit.html?users_id=${users_id}`
}

async function deleteUser(users_id) {
	const confirmed = confirm("Apakah Anda yakin ingin menghapus pengguna ini?");
	if (!confirmed) {
		return; // Jika pengguna membatalkan, hentikan eksekusi fungsi
	}

	// Tampilkan modal preloading
	const preloadingModal = document.getElementById("preloading-modal");
	preloadingModal.style.display = "block";

	try {

		const response = await fetch(`http://localhost/smkti/FE-BE-galeri/restapi/api/users/${users_id}`, {
			method: "DELETE",
			headers: {
				"Authorization": `Bearer ${token}`,
				"Content-Type": "application/json"
			}
		});

		if (!response.ok) {
			const errorData = await response.json();

			// jika token telah kadalwarsa
			if (response.status === 401) {
				localStorage.clear()
				alert(errorData.message)
				window.location.href = "/index.html";
			}

			throw new Error(errorData.message);
		}

		window.location = "pengguna.html"
	} catch (error) {
		console.error("Error:", error);
		alert(error.message);
	} finally {
		// Sembunyikan modal preloading
		preloadingModal.style.display = 'none';
	}
}

async function getUsers() {
	try {
		isLoading = true; // Set flag menjadi true saat mulai memuat data

		const tbody = document.querySelector("#pengguna .table tbody");
		tbody.innerHTML = ""; // Kosongkan tabel sebelum mengisi

		const tr = document.createElement("tr");
		tr.innerHTML = `<td colspan="6">Sedang mengambil data...</td>`;
		tbody.appendChild(tr);
		const response = await fetch("http://localhost/smkti/FE-BE-galeri/restapi/api/users", {
			method: "GET",
			headers: {
				"Authorization": `Bearer ${token}`,
				"Content-Type": "application/json"
			}
		});

		if (!response.ok) {
			isLoading = false;
			const errorData = await response.json();

			// jika token telah kadalwarsa
			if (response.status === 401) {
				localStorage.clear()
				alert(errorData.message)
				window.location.href = "/index.html";
			}

			tbody.innerHTML = "";
			tr.innerHTML = `<td colspan="6">${errorData.message}</td>`;
			tbody.appendChild(tr);
			throw new Error("Gagal memuat data pengguna");
		}

		const result = await response.json();
		const users = result.data;
		isLoading = false;

		if (users.length === 0) {
			tbody.innerHTML = "";
			tr.innerHTML = `<td colspan="6">Data Kosong</td>`;
			tbody.appendChild(tr);
		}

		tbody.innerHTML = ""; // Kosongkan tabel sebelum mengisi

		users.forEach((user, index) => {
			const tr = document.createElement("tr");
			tr.innerHTML = `
					<td>${index + 1}</td>
					<td>${user.nama}</td>
					<td>${user.no_telpon}</td>
					<td>${user.email}</td>
					<td>${user.level}</td>
					<td><button class="btn btn-small" onclick="editUser(${user.id})">Edit</button> <button class="btn btn-small btn-danger" onclick="deleteUser(${user.id})">Delete</button></td>
			`;
			tbody.appendChild(tr);
		});
	} catch (error) {
		console.error("Error:", error);
	}
}
