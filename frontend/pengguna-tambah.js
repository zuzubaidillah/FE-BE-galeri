// Add JS here
document.addEventListener("DOMContentLoaded", () => {
	console.log("Page Pengguna")
	getUsers()
});

let isLoading = false;
document.getElementById("btn-refresh").addEventListener("click", (e) => {
	// Cek apakah sedang memuat data
	if (isLoading === false) {
		getUsers();
	}
})

async function getUsers() {
	const token = localStorage.getItem("token");
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
					<td><button onclick="editUser(${user.id})">Edit</button> <button onclick="deleteUser(${user.id})">Delete</button></td>
			`;
			tbody.appendChild(tr);
		});
	} catch (error) {
		console.error("Error:", error);
	}
}