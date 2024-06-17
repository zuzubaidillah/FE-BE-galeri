// Add JS here
document.addEventListener("DOMContentLoaded", () => {
	let isLoading = false;
	const token = localStorage.getItem("token");

	console.log("Page Pengguna Tambah")

	// Event listener untuk form submit
	document.querySelector('.form').addEventListener('submit', function(e) {
		e.preventDefault();
		const nama = document.getElementById('nama').value;
		const no_telpon = document.getElementById('no_telpon').value;
		const email = document.getElementById('email').value;
		const password = document.getElementById('password').value;
		const level = document.getElementById('level').value;

		const data = {
			nama,
			no_telpon,
			email,
			password,
			level
		};

		if (isLoading === false) {
			submitUser(data);
		}
	});

	// Fungsi untuk mengirim data pengguna ke server
	async function submitUser(data) {
		const messageElement = document.getElementById('message');
		isLoading = true
		try {
			const response = await fetch("http://localhost/smkti/FE-BE-galeri/restapi/api/users", {
				method: "POST",
				headers: {
					"Authorization": `Bearer ${token}`,
					"Content-Type": "application/json"
				},
				body: JSON.stringify(data)
			});

			isLoading = false
			if (!response.ok) {
				const errorData = await response.json();
				if (response.status === 401) {
					localStorage.clear()
					alert(errorData.message)
					window.location.href = "/index.html";
				}else if (response.status === 403) {
					throw new Error(errorData.message);
				}else {
					throw new Error(errorData.message);
				}
			}

			// ketika berhasil pindah halaman
			window.location = "/pengguna.html"
		} catch (error) {
			console.error("Error:", error);
			messageElement.innerText = `Error: ${error.message}`;
			messageElement.style.color = 'red';
		}
	}
});