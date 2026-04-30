const map = L.map("map").setView([-1.5, 114.5], 6.5);

L.control
  .fullscreen({
    position: "topleft",
    title: "Full Screen",
    titleCancel: "Exit Full Screen",
  })
  .addTo(map);

const allPolygons = [];
const allMarkers = [];

map.createPane("paneProvinsi");
map.getPane("paneProvinsi").style.zIndex = 400;

map.createPane("paneKabupaten");
map.getPane("paneKabupaten").style.zIndex = 400;

map.createPane("paneKecamatan");
map.getPane("paneKecamatan").style.zIndex = 400;

map.createPane("paneHutanAdat");
map.getPane("paneHutanAdat").style.zIndex = 400;

const satellite = L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    attribution: "Tiles © Esri",
  },
);

const street = L.tileLayer(
  "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
  {
    attribution: "© OpenStreetMap",
  },
);

const topo = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
  attribution: "© OpenTopoMap",
});

const light = L.tileLayer(
  "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
  {
    attribution: "© CartoDB",
  },
);

const dark = L.tileLayer(
  "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png",
  {
    attribution: "© CartoDB",
  },
);

const labels = L.tileLayer(
  "https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}",
  {
    attribution: "© Esri",
  },
);

const osmLabels = L.tileLayer(
  "https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png",
  {
    attribution: "© OpenStreetMap © CartoDB",
    pane: "labels",
  },
);

map.createPane("labels");
map.getPane("labels").style.zIndex = 650;
map.getPane("labels").style.pointerEvents = "none";

const hybrid = L.layerGroup([satellite, osmLabels]);

hybrid.addTo(map);

L.control
  .layers({
    Hybrid: hybrid,
    // "Satellite": satellite,
    Street: street,
    Topographic: topo,
    Light: light,
    Dark: dark,
  })
  .addTo(map);

map.on("click", function (e) {
  console.log("LatLng:", e.latlng.lat, e.latlng.lng);
});

let totalHutanAdat = 0;
let totalProvinsi = 0;
let totalKabupaten = 0;
let totalKecamatan = 0;
let legend;

const layerProvinsi = L.layerGroup().addTo(map);
const layerKabupaten = L.layerGroup().addTo(map);
const layerKecamatan = L.layerGroup(); // default OFF
const layerHutanAdat = L.layerGroup(); // default OFF

let isLoaded = {
  provinsi: false,
  kabupaten: false,
  kecamatan: false,
  hutan_adat: false,
};

let layerState = {
  provinsi: true,
  kabupaten: true,
  kecamatan: false,
  hutan_adat: false,
};

function loadHutanAdat() {
  if (isLoaded.hutan_adat) return;

  showLoading();

  fetch("api/hutan_adat.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      // totalHutanAdat = data.hutan_adat.length;
      totalHutanAdat = new Set(data.hutan_adat.map((item) => item.id)).size;

      data.hutan_adat.forEach((item) => {
        const hutanAdat = L.polygon(item.tanah.geom_area, {
          pane: "paneHutanAdat",
          color: "#00ff00",
          weight: 2,
          fillOpacity: 0.35,
        }).addTo(layerHutanAdat);

        allPolygons.push(hutanAdat);

        hutanAdat.on("click", (e) => {
          showHutanAdatModal(item.id, e.latlng);
        });
        // const center = getCentroid(item.tanah.geom_area);
        // buka untuk dapat pin icon marker
        // L.marker(center).addTo(map);
      });

      updateLegend();
      isLoaded.hutan_adat = true;
      createTotalMarker(totalHutanAdat, "hutan_adat");
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data hutan adat");
    })
    .finally(() => {
      hideLoading();
    });
}

function loadProvinsi() {
  if (isLoaded.provinsi) return;

  showLoading();

  fetch("api/provinsi.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      // totalProvinsi = data.provinsi.length;
      totalProvinsi = new Set(data.provinsi.map((item) => item.id)).size;

      data.provinsi.forEach((item) => {
        const provinsi = L.polygon(item.tanah.geom_area, {
          pane: "paneProvinsi",
          color: "#FF4400",
          weight: 2,
          fillOpacity: 0.1,
        }).addTo(layerProvinsi);

        allPolygons.push(provinsi);

        provinsi.on("click", (e) => {
          showProvinsiModal(item.id_polygon, e.latlng);
        });
        // const center = getCentroid(item.tanah.geom_area);
        // buka untuk dapat pin icon marker
        // L.marker(center).addTo(map);
      });

      updateLegend();
      isLoaded.provinsi = true;
      createTotalMarker(totalProvinsi, "provinsi");
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data provinsi");
    })
    .finally(() => {
      hideLoading();
    });
}

function loadKabupaten() {
  if (isLoaded.kabupaten) return;

  showLoading();

  fetch("api/kabupaten.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      // totalKabupaten = data.kabupaten.length;
      totalKabupaten = new Set(data.kabupaten.map((item) => item.id)).size;

      data.kabupaten.forEach((item) => {
        const kabupaten = L.geoJSON(item.tanah.geom_area, {
          pane: "paneKabupaten",
          color: "#00AAFF",
          weight: 2,
          fillOpacity: 0.1,
        }).addTo(layerKabupaten);

        allPolygons.push(kabupaten);

        kabupaten.on("click", (e) => {
          showKabupatenModal(item.id_polygon, e.latlng);
        });
        // const center = getCentroid(item.tanah.geom_area);
        // buka untuk dapat pin icon marker
        // L.marker(center).addTo(map);
      });

      updateLegend();
      isLoaded.kabupaten = true;
      createTotalMarker(totalKabupaten, "kabupaten");
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data kabupaten");
    })
    .finally(() => {
      hideLoading();
    });
}

function loadKecamatan() {
  if (isLoaded.kecamatan) return;

  showLoading();

  fetch("api/kecamatan.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      // totalKecamatan = data.kecamatan.length;
      totalKecamatan = new Set(data.kecamatan.map((item) => item.id)).size;

      data.kecamatan.forEach((item) => {
        const kecamatan = L.polygon(item.tanah.geom_area, {
          pane: "paneKecamatan",
          color: "#ffea00",
          weight: 2,
          fillOpacity: 0.1,
        }).addTo(layerKecamatan);

        allPolygons.push(kecamatan);

        kecamatan.on("click", (e) => {
          showKecamatanModal(item.id_polygon, e.latlng);
        });
        const center = getCentroid(item.tanah.geom_area);
        // buka untuk dapat pin icon marker
        // L.marker(center).addTo(map);
      });

      updateLegend();
      isLoaded.kecamatan = true;
      createTotalMarker(totalKecamatan, "kecamatan");
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data kecamatan");
    })
    .finally(() => {
      hideLoading();
    });
}

function updateLegend() {
  // kalau sudah ada, hapus dulu
  if (legend) {
    legend.remove();
  }

  legend = L.control({ position: "topright" });

  legend.onAdd = function () {
    const div = L.DomUtil.create("div", "info legend");

    div.innerHTML = `
        <div style="
            background:white;
            padding:10px;
            border-radius:8px;
            box-shadow:0 3px 8px rgba(0,0,0,0.2);
            font-size:14px;
        ">
            <strong>Keterangan</strong>

        <div>
            <input type="checkbox" id="chkProvinsi" ${layerState.provinsi ? "checked" : ""}>
            <span style="background:#FF4400; width:18px; height:18px; display:inline-block;"></span>
            Provinsi (${totalProvinsi})
        </div>

        <div>
            <input type="checkbox" id="chkKabupaten" ${layerState.kabupaten ? "checked" : ""}>
            <span style="background:#00AAFF; width:18px; height:18px; display:inline-block;"></span>
            Kabupaten (${totalKabupaten})
        </div>

        <div>
            <input type="checkbox" id="chkKecamatan" ${layerState.kecamatan ? "checked" : ""}>
            <span style="background:#ffea00; width:18px; height:18px; display:inline-block;"></span>
            Kecamatan (${totalKecamatan})
        </div>

        <div>
            <input type="checkbox" id="chkHutanAdat" ${layerState.hutan_adat ? "checked" : ""}>
            <span style="background:#00ff00; width:18px; height:18px; display:inline-block;"></span>
            Hutan Adat (${totalHutanAdat})
        </div>

        </div>
    `;

    L.DomEvent.disableClickPropagation(div);
    L.DomEvent.disableScrollPropagation(div);

    return div;
  };

  legend.addTo(map);

  setTimeout(() => {
    document.getElementById("chkProvinsi").addEventListener(
      "change",
      debounce(function () {
        layerState.provinsi = this.checked;
        if (this.checked) {
          loadProvinsi();
          map.addLayer(layerProvinsi);
        } else {
          map.removeLayer(layerProvinsi);
        }
      }),
    );

    document.getElementById("chkKabupaten").addEventListener(
      "change",
      debounce(function () {
        layerState.kabupaten = this.checked;
        if (this.checked) {
          loadKabupaten();
          map.addLayer(layerKabupaten);
        } else {
          map.removeLayer(layerKabupaten);
        }
      }),
    );

    document.getElementById("chkKecamatan").addEventListener(
      "change",
      debounce(function () {
        layerState.kecamatan = this.checked;
        if (this.checked) {
          loadKecamatan();
          map.addLayer(layerKecamatan);
        } else {
          map.removeLayer(layerKecamatan);
        }
      }),
    );

    document.getElementById("chkHutanAdat").addEventListener(
      "change",
      debounce(function () {
        layerState.hutan_adat = this.checked;
        if (this.checked) {
          loadHutanAdat();
          map.addLayer(layerHutanAdat);
        } else {
          map.removeLayer(layerHutanAdat);
        }
      }),
    );
  }, 100);
}

let totalMarker;

function createTotalMarker(total, type) {
  const group = L.featureGroup(allPolygons);
  const center = group.getBounds().getCenter();

  totalMarker = L.marker(center, {
    icon: L.divIcon({
      className: "total-marker",
      // buka untuk dapat total marker
      // html: `
      //     <div style="
      //         background: ${type === 'hutan_adat' ? '#00ff00' : type === 'kabupaten' ? '#00AAFF' : '#FF4400'};
      //         color:white;
      //         width:24px;
      //         height:24px;
      //         border-radius:50%;
      //         display:flex;
      //         align-items:center;
      //         justify-content:center;
      //         font-size:16px;
      //         font-weight:700;
      //         box-shadow:0 3px 8px rgba(0,0,0,.35);
      //     ">
      //         ${total}
      //     </div>
      //     `
    }),
  }).addTo(map);

  totalMarker.on("click", () => {
    map.fitBounds(group.getBounds());
  });

  toggleLayers();
}

function toggleLayers() {
  const zoom = map.getZoom();

  // jangan remove polygon lagi
  // allPolygons.forEach((p) => map.addLayer(p));

  if (zoom < 14) {
    // allPolygons.forEach(p => map.removeLayer(p));
    // allMarkers.forEach(m => map.removeLayer(m));
    map.addLayer(totalMarker);
  } else {
    //     allPolygons.forEach(p => map.addLayer(p));
    //     allMarkers.forEach(m => map.addLayer(m));
    map.removeLayer(totalMarker);
  }
}

// function showHutanAdatModal(item, latlng) {
//   document.querySelector(".modal-title1").innerText = "Informasi Hutan Adat";
//   document.getElementById("namaHutanAdat").innerText = item.nama_hutan_adat;
//   document.getElementById("nomorSk").innerText = item.nomor_sk;
//   document.getElementById("tanggalSk").innerText = item.tanggal_sk;
//   document.getElementById("statusKawasan").innerText = item.status_kawasan;
//   document.getElementById("namaDesaHutanAdat").innerText = item.desa.nama_desa;
//   document.getElementById("namaKecamatanHutanAdat").innerText =
//     item.desa.nama_kecamatan;
//   document.getElementById("namaKabupatenHutanAdat").innerText =
//     item.desa.nama_kabupaten;
//   document.getElementById("namaKelompokTani").innerText =
//     item.kelompok_tani.nama_kelompok_tani;
//   document.getElementById("kategoriKelompok").innerText =
//     item.kelompok_tani.kategori_kelompok;
//   document.getElementById("deskripsiKelompokTani").innerText =
//     item.kelompok_tani.deskripsi;
//   document.getElementById("alamatKelompokTani").innerText =
//     item.kelompok_tani.alamat;
//   document.getElementById("tahunBentuk").innerText =
//     item.kelompok_tani.tahun_bentuk;
//   document.getElementById("statusKelompok").innerText =
//     item.kelompok_tani.status_kelompok;
//   document.getElementById("totalAnggota").innerText =
//     item.kelompok_tani.total_anggota;

//   document.getElementById("namaLahanHutanAdat").innerText =
//     item.tanah.nama_lahan;
//   document.getElementById("legalitasLahanHutanAdat").innerText =
//     item.tanah.legalitas_lahan;
//   document.getElementById("luasHaHutanAdat").innerText = item.tanah.luas_ha;
//   document.getElementById("alamatLokasiHutanAdat").innerText =
//     item.tanah.alamat_lokasi;
//   document.getElementById("keteranganHutanAdat").innerText =
//     item.tanah.keterangan;
//   document.getElementById("sudahValidasiHutanAdat").innerText =
//     item.tanah.sudah_validasi;
//   document.getElementById("tanggalValidasiHutanAdat").innerText =
//     item.tanah.tanggal_validasi;
//   document.getElementById("sejarahHutanAdat").innerText = item.tanah.sejarah;
//   document.getElementById("centroidLatHutanAdat").innerText =
//     item.tanah.centroid_lat;
//   document.getElementById("centroidLngHutanAdat").innerText =
//     item.tanah.centroid_lng;

//   let tableBodyPetaniKelompok = document.getElementById(
//     "petaniKelompokTableBody",
//   );
//   tableBodyPetaniKelompok.innerHTML = ""; // reset dulu

//   if (
//     item.kelompok_tani.petani_kelompok &&
//     item.kelompok_tani.petani_kelompok.length > 0
//   ) {
//     item.kelompok_tani.petani_kelompok.forEach((data, index) => {
//       let row = `
//                 <tr>
//                     <td>${data.petani.nama_lengkap}</td>
//                     <td>${data.petani.nama_panggilan}</td>
//                     <td>${data.petani.jenis_kelamin}</td>
//                     <td>${data.petani.umur}</td>
//                     <td>${data.petani.status_petani}</td>
//                     <td>${data.petani.alamat}</td>
//                     <td><img src="${data.petani.foto}" alt="Foto Petani" width="50" height="50" class="img-fluid rounded-circle"></td>
//                 </tr>
//             `;
//       tableBodyPetaniKelompok.innerHTML += row;
//     });
//   } else {
//     tableBodyPetaniKelompok.innerHTML = `
//                 <tr>
//                     <td colspan="5" style="text-align:center;">Tidak ada data petani</td>
//                 </tr>
//             `;
//   }

//   let tableBodyPolygon = document.getElementById("polygonHutanAdatTableBody");
//   tableBodyPolygon.innerHTML = "";

//   if (item.tanah.geom_area && item.tanah.geom_area.length > 0) {
//     let rowLat = `<tr><th>Latitude</th>`;
//     let rowLng = `<tr><th>Longitude</th>`;

//     item.tanah.geom_area.forEach((point, index) => {
//       rowLat += `<td>${point[0]}</td>`;
//       rowLng += `<td>${point[1]}</td>`;
//     });

//     rowLat += `</tr>`;
//     rowLng += `</tr>`;

//     tableBodyPolygon.innerHTML = rowLat + rowLng;
//   } else {
//     tableBodyPolygon.innerHTML = `
//                 <tr>
//                     <td colspan="10" style="text-align:center;">
//                         Tidak ada data koordinat
//                     </td>
//                 </tr>
//             `;
//   }

//   new bootstrap.Modal(document.getElementById("hutanAdatModal")).show();
// }

function showHutanAdatModal(id, latlng) {
  document.querySelector(".modal-title1").innerText = "Informasi Hutan Adat";

  new bootstrap.Modal(document.getElementById("hutanAdatModal")).show();

  // tampilkan loading
  document.getElementById("loadingDetail").style.display = "block";
  document.getElementById("hutanAdatModal").style.display = "none";

  fetch(`api/detail_polygon.php?id=${id}&&tipe=hutan_adat`)
    .then((res) => res.json())
    .then((res) => {
      // sembunyikan loading
      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("hutanAdatModal").style.display = "block";

      if (!res.status) {
        alert("Data tidak ditemukan");
        document.getElementById("hutanAdatModal").innerHTML = `
          <p style="text-align:center;">Data tidak ditemukan</p>
        `;
        return;
      }

      const data = res.data;
      console.log(id);
      console.log(data);

      // contoh isi data ke modal
      document.getElementById("namaHutanAdat").innerText =
        data.nama_hutan_adat ?? "";
      document.getElementById("nomorSk").innerText = data.nomor_sk ?? "";
      document.getElementById("tanggalSk").innerText = data.tanggal_sk ?? "";
      document.getElementById("statusKawasan").innerText =
        data.status_kawasan ?? "";
      document.getElementById("namaDesaHutanAdat").innerText =
        data.nama_desa ?? "";
      document.getElementById("namaKecamatanHutanAdat").innerText =
        data.nama_kecamatan ?? "";
      document.getElementById("namaKabupatenHutanAdat").innerText =
        data.nama_kabupaten ?? "";

      document.getElementById("namaMasyarakatHukumAdat").innerText =
        data.nama_masyarakat_hukum_adat ?? "";
      document.getElementById("namaKategoriKelompok").innerText =
        data.nama_kategori_kelompok ?? "";
      document.getElementById("tahunBentuk").innerText =
        data.tahun_bentuk ?? "";
      document.getElementById("statusMasyarakatHukumAdat").innerText =
        data.status_masyarakat_hukum_adat == "aktif"
          ? "Aktif"
          : ("Nonaktif" ?? "");
      document.getElementById("totalAnggotaMasyarakatHukumAdat").innerText =
        data.total_anggota_masyarakat_hukum_adat ?? "";
      document.getElementById("alamatMasyarakatHukumAdat").innerText =
        data.alamat_masyarakat_hukum_adat ?? "";
      document.getElementById("deskripsiMasyarakatHukumAdat").innerText =
        data.deskripsi ?? "";

      document.getElementById("namaLahanHA").innerText = data.nama_lahan ?? "";
      document.getElementById("legalitasLahanHA").innerText =
        data.legalitas_lahan ?? "";
      document.getElementById("luasHaHA").innerText = data.luas_ha ?? "";
      document.getElementById("sejarahHA").innerText = data.sejarah ?? "";
      document.getElementById("alamatLokasiHA").innerText =
        data.alamat_lokasi ?? "";
      document.getElementById("keteranganHA").innerText = data.keterangan ?? "";
      document.getElementById("sudahValidasiHA").innerText =
        data.sudah_validasi ?? "";
      document.getElementById("tanggalValidasiHA").innerText =
        data.tanggal_validasi ?? "";

      document.getElementById("petaniKelompokTableBody").innerHTML = `
        <tr>
          <td colspan="7" style="text-align:center;">Loading...</td>
        </tr>
      `;

      // ambil data petani
      fetch(`api/list_petani.php?id=${data.id_kelompok_tani}`)
        .then((res) => res.json())
        .then((resPetani) => {
          console.log(resPetani);
          const tbody = document.getElementById("petaniKelompokTableBody");
          tbody.innerHTML = "";

          // ambil data dengan aman
          let petaniList = [];

          if (Array.isArray(resPetani.data)) {
            petaniList = resPetani.data;
          } else if (Array.isArray(resPetani)) {
            petaniList = resPetani;
          } else if (resPetani.data && typeof resPetani.data === "object") {
            petaniList = [resPetani.data]; // bungkus jadi array
          }

          if (petaniList.length === 0) {
            tbody.innerHTML = `
              <tr>
                <td colspan="7" style="text-align:center;">Tidak ada data petani</td>
              </tr>
            `;
            return;
          }

          petaniList.forEach((item) => {
            const row = `
              <tr>
                <td>${item.nama_lengkap ?? "-"}</td>
                <td>${item.nama_panggilan ?? "-"}</td>
                <td>${item.jenis_kelamin === "L" ? "Laki-laki" : "Perempuan"}</td>
                <td>${hitungUmur(item.tanggal_lahir)}</td>
                <td>${item.status_petani === "aktif" ? "Aktif" : "Nonaktif"}</td>
                <td>${item.alamat ?? "-"}</td>
                <td>
                  ${
                    item.foto
                      ? `<img src="uploads/petani/${item.foto}" width="50" height="50" class="img-fluid rounded-circle">`
                      : `<img src="assets/image/petani_placeholder.jpg" width="50" height="50" class="img-fluid rounded-circle">`
                  }
                </td>
              </tr>
            `;

            tbody.insertAdjacentHTML("beforeend", row);
          });
        })
        .catch((err) => {
          console.error(err);

          const tbody = document.getElementById("petaniKelompokTableBody");
          tbody.innerHTML = `
            <tr>
              <td colspan="7" style="text-align:center; color:red;">
                Gagal memuat data petani
              </td>
            </tr>
          `;
        });
    })
    .catch((err) => {
      console.error(err);

      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("hutanAdatModal").style.display = "block";

      document.getElementById("hutanAdatModal").innerHTML = `
        <p style="text-align:center; color:red;">Gagal memuat data</p>
      `;
    });
}

function showProvinsiModal(id, latlng) {
  document.querySelector(".modal-title1").innerText = "Informasi Provinsi";

  new bootstrap.Modal(document.getElementById("provinsiModal")).show();

  // tampilkan loading
  document.getElementById("loadingDetail").style.display = "block";
  document.getElementById("provinsiModal").style.display = "none";

  fetch(`api/detail_polygon.php?id=${id}&&tipe=provinsi`)
    .then((res) => res.json())
    .then((res) => {
      // sembunyikan loading
      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("provinsiModal").style.display = "block";

      if (!res.status) {
        alert("Data tidak ditemukan");
        document.getElementById("provinsiModal").innerHTML = `
          <p style="text-align:center;">Data tidak ditemukan</p>
        `;
        return;
      }

      const data = res.data;
      console.log(data);

      // contoh isi data ke modal
      document.getElementById("namaProvinsi").innerText = data.nama_provinsi;
    })
    .catch((err) => {
      console.error(err);

      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("provinsiModal").style.display = "block";

      document.getElementById("provinsiModal").innerHTML = `
        <p style="text-align:center; color:red;">Gagal memuat data</p>
      `;
    });
}

function showKabupatenModal(id, latlng) {
  document.querySelector(".modal-title1").innerText = "Informasi Kabupaten";

  new bootstrap.Modal(document.getElementById("kabupatenModal")).show();

  // tampilkan loading
  document.getElementById("loadingDetail").style.display = "block";
  document.getElementById("kabupatenModal").style.display = "none";

  fetch(`api/detail_polygon.php?id=${id}&&tipe=kabupaten`)
    .then((res) => res.json())
    .then((res) => {
      // sembunyikan loading
      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("kabupatenModal").style.display = "block";

      if (!res.status) {
        alert("Data tidak ditemukan");
        document.getElementById("kabupatenModal").innerHTML = `
          <p style="text-align:center;">Data tidak ditemukan</p>
        `;
        return;
      }

      const data = res.data;
      console.log(data);

      // contoh isi data ke modal
      document.getElementById("namaKabupaten").innerText = data.nama_kabupaten;
    })
    .catch((err) => {
      console.error(err);

      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("kabupatenModal").style.display = "block";

      document.getElementById("kabupatenModal").innerHTML = `
        <p style="text-align:center; color:red;">Gagal memuat data</p>
      `;
    });
}

function showKecamatanModal(id, latlng) {
  document.querySelector(".modal-title1").innerText = "Informasi Kecamatan";

  new bootstrap.Modal(document.getElementById("kecamatanModal")).show();

  // tampilkan loading
  document.getElementById("loadingDetail").style.display = "block";
  document.getElementById("kecamatanModal").style.display = "none";

  fetch(`api/detail_polygon.php?id=${id}&&tipe=kecamatan`)
    .then((res) => res.json())
    .then((res) => {
      // sembunyikan loading
      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("kecamatanModal").style.display = "block";

      if (!res.status) {
        alert("Data tidak ditemukan");
        document.getElementById("kecamatanModal").innerHTML = `
          <p style="text-align:center;">Data tidak ditemukan</p>
        `;
        return;
      }

      const data = res.data;
      console.log(data);

      // contoh isi data ke modal
      document.getElementById("namaKecamatan").innerText = data.nama_kecamatan;
    })
    .catch((err) => {
      console.error(err);

      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("kecamatanModal").style.display = "block";

      document.getElementById("kecamatanModal").innerHTML = `
        <p style="text-align:center; color:red;">Gagal memuat data</p>
      `;
    });
}

function getCentroid(coords) {
  let lat = 0,
    lng = 0;

  coords.forEach((c) => {
    lat += c[0];
    lng += c[1];
  });

  return [lat / coords.length, lng / coords.length];
}

map.on("zoomend", toggleLayers);

function hitungUmur(tanggalLahir) {
  if (!tanggalLahir) return "-";

  const today = new Date();
  const birthDate = new Date(tanggalLahir);

  let umur = today.getFullYear() - birthDate.getFullYear();
  const m = today.getMonth() - birthDate.getMonth();

  // koreksi kalau belum ulang tahun tahun ini
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    umur--;
  }

  return umur;
}

loadProvinsi();
loadKabupaten();

function showLoading() {
  document.getElementById("mapLoading").style.display = "block";
}

function hideLoading() {
  document.getElementById("mapLoading").style.display = "none";
}

function debounce(fn, delay = 300) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn.apply(this, args), delay);
  };
}
