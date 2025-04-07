$(document).ready(function () {
  $("#myTable").DataTable({
    columnDefs: [
      {
        order: [[0, 'asc']],
        targets: [1, 2], // Kolom Harga Beli dan Harga Jual
        render: function (data, type, row) {
          console.log("Original Data:", data); // Menampilkan data asli

          // Menghapus simbol "Rp" dari awal data
          let cleanedData = data.replace("Rp", "").trim();
          console.log("Data after cleaning 'Rp':", cleanedData); // Setelah menghapus 'Rp'
          
          if (type === "sort") {
            // Untuk sorting, pastikan data yang tersisa adalah angka dan jaga titik
            // cleanedData = cleanedData.replace(/\./g, ""); // Menghapus titik untuk sorting
            cleanedData = cleanedData.replace(/[^0-9,-]+/g, ""); // Menghapus karakter selain angka dan koma
            // Konversi menjadi angka untuk sorting
            return parseFloat(cleanedData.replace(",", ".")); // Jika ada koma sebagai pemisah desimal
          }

          // Untuk tampilan, kembalikan data yang sudah dibersihkan tanpa "Rp", tapi jaga titik
          return cleanedData;
        },
      },
    ],
  });
});
