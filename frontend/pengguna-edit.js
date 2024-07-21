// Add JS here
document.addEventListener("DOMContentLoaded", () => {
	console.log("Page Pengguna Edit")
	let isLoading = false;
	const token = localStorage.getItem("token");

	// Ambil parameter users_id dari URL
	const urlParams = new URLSearchParams(window.location.search);
	const userId = urlParams.get('users_id');

	if (userId) {
		console.log(`User ID: ${userId}`);
		// Lakukan operasi lain dengan userId, seperti mengambil data pengguna dari server
		fetchUserData(userId);
	} else {
		console.error('Parameter users_id tidak ditemukan di URL');
	}

	// Fungsi untuk mengambil data pengguna dari server
	async function fetchUserData(userId) {
		const messageElement = document.getElementById('message');

		try {
			isLoading = true
			const response = await fetch(`http://localhost/smkti/FE-BE-galeri/restapi/api/users/${userId}`, {
				method: 'GET',
				headers: {
					'Authorization': `Bearer ${token}`,
					'Content-Type': 'application/json'
				}
			});

			isLoading = false
			if (!response.ok) {
				const errorData = await response.json();
				if (response.status === 401) {
					localStorage.clear()
					alert(errorData.message)
					window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html";
				}else if (response.status === 403) {
					throw new Error(errorData.message);
				}else {
					throw new Error(errorData.message);
				}
			}

			const result = await response.json();
			console.log('Data pengguna:', result.data);
			// Isi form dengan data pengguna yang diambil
			document.getElementById('nama').value = result.data.nama;
			document.getElementById('no_telpon').value = result.data.no_telpon;
			document.getElementById('email').value = result.data.email;
			document.getElementById('level').value = result.data.level;
		} catch (error) {
			console.error('Error:', error);
			messageElement.innerText = `Error: ${error.message}`;
			messageElement.style.color = 'red';
		}
	}

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
			level
		};

		if (password) {
			data.password = password
		}

		if (isLoading === false) {
			submitUser(data);
		}
	});

	// Fungsi untuk mengirim data pengguna ke server
	async function submitUser(data) {
		const messageElement = document.getElementById('message');
		isLoading = true
		try {
			const response = await fetch(`http://localhost/smkti/FE-BE-galeri/restapi/api/users/${userId}`, {
				method: "PUT",
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
					window.location.href = "http://localhost/smkti/FE-BE-galeri/frontend/index.html";
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