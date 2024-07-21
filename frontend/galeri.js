const token = localStorage.getItem("token");
let debounceTimeout;

document.addEventListener("DOMContentLoaded", () => {
	if (!token) {
		localStorage.clear()
		window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html"; // Redirect ke halaman login jika tidak ada token
		return;
	}

	// Fungsi debounce
	function debounce(func, delay) {
		return function (...args) {
			clearTimeout(debounceTimeout);
			debounceTimeout = setTimeout(() => {
				func.apply(this, args);
			}, delay);
		};
	}

	// Event listener untuk input filter dengan debounce
	document.getElementById("filter_q").addEventListener("input", debounce((event) => {
		const query = event.target.value;
		getGaleri(query); // Panggil fungsi untuk mengambil data galeri dengan filter
	}, 700)); // Delay 300 ms

	getGaleri(); // Panggil fungsi untuk mengambil data galeri saat halaman dimuat
});

// Fungsi untuk mengambil data galeri
async function getGaleri(query = "") {

	// Tampilkan modal preloading
	const preloadingModal = document.getElementById("preloading-modal");
	preloadingModal.style.display = "block";

	try {
		let url = "http://localhost/smkti/FE-BE-galeri/restapi/api/galeri";
		if (query) {
			url += `?filter_q=${query}`;
		}

		const response = await fetch(url, {
			method: "GET",
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
				window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html";
			}
			throw new Error("Gagal memuat data galeri");
		}

		const result = await response.json();
		const galeri = result.data;

		const galeriContainer = document.querySelector(".galeri-container");
		galeriContainer.innerHTML = ""; // Kosongkan kontainer galeri sebelum mengisi

		galeri.forEach((item, index) => {
			const galeriItem = document.createElement("div");
			galeriItem.className = "galeri-item";
			galeriItem.innerHTML = `
					<img src="${item.file}" alt="${item.nama}">
					<p>${item.nama}</p>
					<button onclick="editNama(${item.id})">Edit Nama</button>
					<button onclick="showEditImageModal(${item.id}, '${item.file}')">Edit Gambar</button>
					<button class="btn-danger" onclick="deleteGaleri(${item.id})">Hapus</button>
			`;
			galeriContainer.appendChild(galeriItem);
		});
	} catch (error) {
		console.error("Error:", error);
	} finally {
		// Sembunyikan modal preloading
		preloadingModal.style.display = "none";
	}
}

// Fungsi untuk menghapus galeri
async function deleteGaleri(id) {
	const confirmed = confirm("Apakah Anda yakin ingin menghapus galeri ini?");
	if (!confirmed) {
		return; // Jika pengguna membatalkan, hentikan eksekusi fungsi
	}

	// Tampilkan modal preloading
	const preloadingModal = document.getElementById("preloading-modal");
	preloadingModal.style.display = "block";

	try {
		const response = await fetch(`http://localhost/smkti/FE-BE-galeri/restapi/api/galeri/${id}`, {
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
				window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html";
			}

			throw new Error(errorData.message);
		}

		getGaleri(); // Refresh data galeri setelah penghapusan
	} catch (error) {
		console.error("Error:", error);
		alert(error.message);
	} finally {
		// Sembunyikan modal preloading
		preloadingModal.style.display = "none";
	}
}

// Fungsi untuk mengedit nama galeri
async function editNama(id) {
	const newName = prompt(`Masukkan nama baru: id ${id}`);
	if (!newName) {
		return; // Jika pengguna membatalkan, hentikan eksekusi fungsi
	}

	// Tampilkan modal preloading
	const preloadingModal = document.getElementById("preloading-modal");
	preloadingModal.style.display = "block";

	try {
		const response = await fetch(`http://localhost/smkti/FE-BE-galeri/restapi/api/galeri/${id}`, {
			method: "PUT",
			headers: {
				"Authorization": `Bearer ${token}`,
				"Content-Type": "application/json"
			},
			body: JSON.stringify({nama: newName})
		});

		if (!response.ok) {
			const errorData = await response.json();

			// jika token telah kadalwarsa
			if (response.status === 401) {
				localStorage.clear()
				alert(errorData.message)
				window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html";
			}

			throw new Error(errorData.message);
		}

		getGaleri(); // Refresh data galeri setelah pengubahan
	} catch (error) {
		console.error("Error:", error);
		alert(error.message);
	} finally {
		// Sembunyikan modal preloading
		preloadingModal.style.display = "none";
	}
}

// Fungsi untuk menampilkan modal edit gambar
function showEditImageModal(id, currentImageUrl) {
	const modal = document.getElementById("edit-image-modal");
	const currentImage = document.getElementById("current-image");
	const newImageFileInput = document.getElementById("new-image-file");
	const submitButton = document.getElementById("submit-new-image");

	currentImage.src = currentImageUrl;
	newImageFileInput.value = "";
	modal.style.display = "block";

	submitButton.onclick = function () {
		const newImageFile = newImageFileInput.files[0];
		if (newImageFile && newImageFile.type.startsWith("image/")) {
			editImage(id, newImageFile);
			modal.style.display = "none";
		} else {
			alert("Silakan pilih file gambar yang valid.");
		}
	};

	document.getElementById("cancel-edit-image").onclick = function () {
		modal.style.display = "none";
	};
}

// Fungsi untuk mengedit gambar galeri
async function editImage(id, newImageFile) {
	try {
		const formData = new FormData();
		formData.append("image", newImageFile);

		const response = await fetch(`http://localhost/smkti/FE-BE-galeri/restapi/api/galeri/${id}/image`, {
			method: "POST",
			headers: {
				"Authorization": `Bearer ${token}`
			},
			body: formData
		});

		if (!response.ok) {
			const errorData = await response.json();

			// jika token telah kadalwarsa
			if (response.status === 401) {
				localStorage.clear()
				alert(errorData.message)
				window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html";
			}

			throw new Error(errorData.message);
		}

		getGaleri(); // Refresh data galeri setelah pengubahan
	} catch (error) {
		console.error("Error:", error);
		alert(error.message);
	}
}