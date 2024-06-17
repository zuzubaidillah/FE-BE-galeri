document.addEventListener("DOMContentLoaded", () => {
	let isLoading = false;
	const token = localStorage.getItem("token");

	// Event listener untuk form submit
	document.querySelector('.form').addEventListener('submit', function(e) {
		e.preventDefault();

		if (isLoading) {
			return; // Jika sedang loading, hentikan eksekusi
		}

		const nama = document.getElementById('nama').value;
		const imageFile = document.getElementById('image').files[0];

		if (!imageFile) {
			showMessage("Silakan pilih file gambar.", "red");
			return;
		}

		const formData = new FormData();
		formData.append("nama", nama);
		formData.append("image", imageFile);

		submitGaleri(formData);
	});

	// Fungsi untuk mengirim data galeri ke server
	async function submitGaleri(formData) {
		const messageElement = document.getElementById('message');
		isLoading = true;

		try {
			const response = await fetch("http://localhost/smkti/FE-BE-galeri/restapi/api/galeri", {
				method: "POST",
				headers: {
					"Authorization": `Bearer ${token}`,
				},
				body: formData
			});

			isLoading = false;

			if (!response.ok) {
				const errorData = await response.json();

				if (response.status === 401) {
					localStorage.clear();
					alert(errorData.message);
					window.location.href = "/index.html";
				}

				throw new Error(errorData.message);
			}

			// ketika berhasil pindah halaman
			window.location.href = "/galeri.html";
		} catch (error) {
			console.error("Error:", error);
			showMessage(`Error: ${error.message}`, "red");
		}
	}

	// Fungsi untuk menampilkan pesan
	function showMessage(message, color) {
		const messageElement = document.getElementById('message');
		messageElement.innerText = message;
		messageElement.style.color = color;
	}
});
