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

map.createPane("paneKaleka");
map.getPane("paneKaleka").style.zIndex = 400;

map.createPane("paneBankBenih");
map.getPane("paneBankBenih").style.zIndex = 400;

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
let totalKaleka = 0;
let totalBankBenih = 0;
let legend;

const layerProvinsi = L.layerGroup().addTo(map);
const layerKabupaten = L.layerGroup().addTo(map);
const layerKecamatan = L.layerGroup(); // default OFF
const layerHutanAdat = L.layerGroup(); // default OFF
const layerKaleka = L.layerGroup(); // default OFF
const layerBankBenih = L.layerGroup(); // default OFF

let isLoaded = {
  provinsi: false,
  kabupaten: false,
  kecamatan: false,
  hutan_adat: false,
  kaleka: false,
  bank_benih: false,
};

let layerState = {
  provinsi: true,
  kabupaten: true,
  kecamatan: false,
  hutan_adat: false,
  kaleka: false,
  bank_benih: false,
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
      loadKabupaten();
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

function loadKaleka() {
  if (isLoaded.kaleka) return;

  showLoading();

  fetch("api/kaleka.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      // totalKaleka = data.kaleka.length;
      totalKaleka = new Set(data.kaleka.map((item) => item.id)).size;

      data.kaleka.forEach((item) => {
        const kaleka = L.polygon(item.tanah.geom_area, {
          pane: "paneKaleka",
          color: "#9500ff",
          weight: 2,
          fillOpacity: 0.35,
        }).addTo(layerKaleka);

        allPolygons.push(kaleka);

        kaleka.on("click", (e) => {
          showKalekaModal(item.id, e.latlng);
        });

        // buka untuk dapat pin icon marker
        const center = getCentroid(item.tanah.geom_area);
        const purpleIcon = new L.Icon({
          iconUrl: "assets/image/marker-purple.png",
          // iconUrl:
          //   "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png",
          // shadowUrl:
          //   "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",

          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          // shadowSize: [41, 41],
        });

        // Marker dengan icon ungu
        L.marker(center, {
          icon: purpleIcon,
        }).addTo(map);
      });

      updateLegend();
      isLoaded.kaleka = true;
      createTotalMarker(totalKaleka, "kaleka");
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data kaleka");
    })
    .finally(() => {
      hideLoading();
    });
}

function loadBankBenih() {
  if (isLoaded.bank_benih) return;

  showLoading();

  fetch("api/bank_benih.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      // totalBankBenih = data.bank_benih.length;
      // totalBankBenih = new Set(data.map((item) => item.titik_koleksi_lat)).size;
      totalBankBenih = data.data.length;

      data.data.forEach((item) => {
        // pastikan latitude dan longitude ada
        if (!item.titik_koleksi_lat || !item.titik_koleksi_lng) return;

        // membuat marker
        const creamIcon = L.icon({
          iconUrl: "assets/image/marker-cream.png",
          // shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",

          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          // shadowSize: [41, 41],
        });

        const bank_benih = L.marker(
          [
            parseFloat(item.titik_koleksi_lat),
            parseFloat(item.titik_koleksi_lng),
          ],
          {
            pane: "paneBankBenih",
            icon: creamIcon,
          },
        ).addTo(layerBankBenih);

        allPolygons.push(bank_benih);

        bank_benih.on("click", (e) => {
          showBankBenihModal(item.id, e.latlng);
        });
        // const center = getCentroid(item.tanah.geom_area);
        // buka untuk dapat pin icon marker
        // L.marker(center).addTo(map);
      });

      updateLegend();
      isLoaded.bank_benih = true;
      createTotalMarker(totalBankBenih, "bank_benih");
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data bank benih");
    })
    .finally(() => {
      hideLoading();
    });
}

function loadTotalFarmer() {
  fetch("api/total_farmer.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);

      const totalLaki = Number(
        data.total_farmer?.find((item) => item.jenis_kelamin === "L")?.total ??
          0,
      );

      const totalPerempuan = Number(
        data.total_farmer?.find((item) => item.jenis_kelamin === "P")?.total ??
          0,
      );

      document.getElementById("totalLaki").innerText = totalLaki;
      document.getElementById("totalPerempuan").innerText = totalPerempuan;

      // Total semua petani
      document.getElementById("totalPetani").innerText =
        totalLaki + totalPerempuan;
    })
    .catch((err) => {
      console.error(err);
      alert("Gagal memuat data total farmer");
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
            <strong data-lang="text_keterangan">Keterangan</strong>

        <div>
            <input type="checkbox" id="chkProvinsi" ${layerState.provinsi ? "checked" : ""}>
            <span style="background:#FF4400; width:18px; height:18px; display:inline-block;"></span>
            <span data-lang="text_provinsi">Provinsi</span> (${totalProvinsi})
        </div>

        <div>
            <input type="checkbox" id="chkKabupaten" ${layerState.kabupaten ? "checked" : ""}>
            <span style="background:#00AAFF; width:18px; height:18px; display:inline-block;"></span>
            <span data-lang="text_kabupaten">Kabupaten</span> (${totalKabupaten})
        </div>

        <div>
            <input type="checkbox" id="chkKecamatan" ${layerState.kecamatan ? "checked" : ""}>
            <span style="background:#ffea00; width:18px; height:18px; display:inline-block;"></span>
            <span data-lang="text_kecamatan">Kecamatan</span> (${totalKecamatan})
        </div>

        <div>
            <input type="checkbox" id="chkHutanAdat" ${layerState.hutan_adat ? "checked" : ""}>
            <span style="background:#00ff00; width:18px; height:18px; display:inline-block;"></span>
            <span data-lang="text_hutan_adat">Hutan Adat</span> (${totalHutanAdat})
        </div>

        <div>
            <input type="checkbox" id="chkKaleka" ${layerState.kaleka ? "checked" : ""}>
            <span style="background:#9500ff; width:18px; height:18px; display:inline-block;"></span>
            <span data-lang="text_kaleka">Kaleka</span> (${totalKaleka})
        </div>

        <div>
            <input type="checkbox" id="chkBankBenih" ${layerState.bank_benih ? "checked" : ""}>
            <span style="background:#f7ebd1; width:18px; height:18px; display:inline-block;"></span>
            <span data-lang="text_bank_benih">Bank Benih</span> (${totalBankBenih})
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

    document.getElementById("chkKaleka").addEventListener(
      "change",
      debounce(function () {
        layerState.kaleka = this.checked;
        if (this.checked) {
          loadKaleka();
          map.addLayer(layerKaleka);
        } else {
          map.removeLayer(layerKaleka);
        }
      }),
    );

    document.getElementById("chkBankBenih").addEventListener(
      "change",
      debounce(function () {
        layerState.bank_benih = this.checked;
        if (this.checked) {
          loadBankBenih();
          map.addLayer(layerBankBenih);
        } else {
          map.removeLayer(layerBankBenih);
        }
      }),
    );
  }, 100);

  setLanguage(localStorage.getItem("language"));
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
      //         background: ${type === 'hutan_adat' ? '#00ff00' : type === 'kabupaten' ? '#00AAFF' : type === 'kaleka' ? '#9500ff' : '#FF4400'};
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
  // document.querySelector(".modal-title-hutan-adat").innerText =
  //   "Informasi Hutan Adat";
  setTranslatedText("modal-title-hutan-adat", "Informasi Hutan Adat");

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
      // document.getElementById("namaHutanAdat").innerText =
      //   data.nama_hutan_adat ?? "";
      // document.getElementById("nomorSk").innerText = data.nomor_sk ?? "";
      // document.getElementById("tanggalSk").innerText = data.tanggal_sk
      //   ? new Date(data.tanggal_sk).toLocaleDateString("id-ID", {
      //       day: "numeric",
      //       month: "long",
      //       year: "numeric",
      //       timeZone: "UTC",
      //     })
      //   : "";
      // document.getElementById("statusKawasan").innerText =
      //   data.status_kawasan ?? "";
      // document.getElementById("namaDesaHutanAdat").innerText =
      //   data.nama_desa ?? "";
      // document.getElementById("namaKecamatanHutanAdat").innerText =
      //   data.nama_kecamatan ?? "";
      // document.getElementById("namaKabupatenHutanAdat").innerText =
      //   data.nama_kabupaten ?? "";

      // document.getElementById("namaMasyarakatHukumAdat").innerText =
      //   data.nama_masyarakat_hukum_adat ?? "";
      // document.getElementById("namaKategoriKelompok").innerText =
      //   data.nama_kategori_kelompok ?? "";
      // document.getElementById("tahunBentuk").innerText =
      //   data.tahun_bentuk ?? "";
      // document.getElementById("statusMasyarakatHukumAdat").innerText =
      //   data.status_masyarakat_hukum_adat == "aktif"
      //     ? "Aktif"
      //     : ("Nonaktif" ?? "");
      // document.getElementById("totalAnggotaMasyarakatHukumAdat").innerText =
      //   data.total_anggota_masyarakat_hukum_adat ?? "";
      // document.getElementById("alamatMasyarakatHukumAdat").innerText =
      //   data.alamat_masyarakat_hukum_adat ?? "";
      // document.getElementById("deskripsiMasyarakatHukumAdat").innerText =
      //   data.deskripsi ?? "";

      // document.getElementById("namaLahanHA").innerText = data.nama_lahan ?? "";
      // document.getElementById("legalitasLahanHA").innerText =
      //   data.legalitas_lahan ?? "";
      // document.getElementById("luasHaHA").innerText = data.luas_ha ?? "";
      // document.getElementById("sejarahHA").innerText = data.sejarah ?? "";
      // document.getElementById("alamatLokasiHA").innerText =
      //   data.alamat_lokasi ?? "";
      // document.getElementById("keteranganHA").innerText = data.keterangan ?? "";
      // document.getElementById("sudahValidasiHA").innerText =
      //   data.sudah_validasi ?? "";
      // document.getElementById("tanggalValidasiHA").innerText =
      //   data.tanggal_validasi ?? "";

      setTranslatedText("namaHutanAdat", data.nama_hutan_adat ?? "");
      setTranslatedText("nomorSk", data.nomor_sk ?? "");
      setTranslatedText(
        "tanggalSk",
        data.tanggal_sk
          ? new Date(data.tanggal_sk).toLocaleDateString("id-ID", {
              day: "numeric",
              month: "long",
              year: "numeric",
              timeZone: "UTC",
            })
          : "",
      );
      setTranslatedText("statusKawasan", data.status_kawasan ?? "");
      setTranslatedText("namaDesaHutanAdat", data.nama_desa ?? "");
      setTranslatedText("namaKecamatanHutanAdat", data.nama_kecamatan ?? "");
      setTranslatedText("namaKabupatenHutanAdat", data.nama_kabupaten ?? "");

      setTranslatedText(
        "namaMasyarakatHukumAdat",
        data.nama_masyarakat_hukum_adat ?? "",
      );
      setTranslatedText(
        "namaKategoriKelompok",
        data.nama_kategori_kelompok ?? "",
      );
      setTranslatedText("tahunBentuk", data.tahun_bentuk ?? "");
      setTranslatedText(
        "statusMasyarakatHukumAdat",
        data.status_masyarakat_hukum_adat == "aktif" ? "Aktif" : "Nonaktif",
      );
      setTranslatedText(
        "totalAnggotaMasyarakatHukumAdat",
        data.total_anggota_masyarakat_hukum_adat ?? "",
      );
      setTranslatedText(
        "alamatMasyarakatHukumAdat",
        data.alamat_masyarakat_hukum_adat ?? "",
      );
      setTranslatedText("deskripsiMasyarakatHukumAdat", data.deskripsi ?? "");

      setTranslatedText("namaLahanHA", data.nama_lahan ?? "");
      setTranslatedText("legalitasLahanHA", data.legalitas_lahan ?? "");
      setTranslatedText("luasHaHA", data.luas_ha ?? "");
      setTranslatedText("sejarahHA", data.sejarah ?? "");
      setTranslatedText("alamatLokasiHA", data.alamat_lokasi ?? "");
      setTranslatedText("keteranganHA", data.keterangan ?? "");
      setTranslatedText("sudahValidasiHA", data.sudah_validasi ?? "");
      setTranslatedText(
        "tanggalValidasiHA",
        data.tanggal_validasi
          ? new Date(data.tanggal_validasi).toLocaleDateString("id-ID", {
              day: "numeric",
              month: "long",
              year: "numeric",
              timeZone: "UTC",
            })
          : "",
      );

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
                    item.foto_profil_petani
                      ? `<img src="admin/uploads/petani/${item.foto_profil_petani}" width="50" height="50" class="img-fluid rounded-circle">`
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
  // document.querySelector(".modal-title-provinsi").innerText =
  //   "Informasi Provinsi";
  setTranslatedText("modal-title-provinsi", "Informasi Provinsi");

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
      // document.getElementById("namaProvinsi").innerText = data.nama_provinsi;
      setTranslatedText("namaProvinsi", data.nama_provinsi);
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
  // document.querySelector(".modal-title-kabupaten").innerText =
  //   "Informasi Kabupaten";
  setTranslatedText("modal-title-kabupaten", "Informasi Kabupaten");

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
      // document.getElementById("namaKabupaten").innerText = data.nama_kabupaten;
      setTranslatedText("namaKabupaten", data.nama_kabupaten);
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
  // document.querySelector(".modal-title-kecamatan").innerText =
  //   "Informasi Kecamatan";
  setTranslatedText("modal-title-kecamatan", "Informasi Kecamatan");

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
      // document.getElementById("namaKecamatan").innerText = data.nama_kecamatan;
      setTranslatedText("namaKecamatan", data.nama_kecamatan);
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

function showKalekaModal(id, latlng) {
  // document.querySelector(".modal-title-kaleka").innerText = "Informasi Kaleka";
  setTranslatedText("modal-title-kaleka", "Informasi Kaleka");

  new bootstrap.Modal(document.getElementById("kalekaModal")).show();

  // tampilkan loading
  document.getElementById("loadingDetail").style.display = "block";
  document.getElementById("kalekaModal").style.display = "none";

  fetch(`api/detail_polygon.php?id=${id}&&tipe=kaleka`)
    .then((res) => res.json())
    .then((res) => {
      // sembunyikan loading
      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("kalekaModal").style.display = "block";

      if (!res.status) {
        alert("Data tidak ditemukan");
        document.getElementById("kalekaModal").innerHTML = `
          <p style="text-align:center;">Data tidak ditemukan</p>
        `;
        return;
      }

      const data = res.data;
      console.log(id);
      console.log(data);

      // contoh isi data ke modal
      // document.getElementById("namaPanggilan").innerText =
      //   data.nama_panggilan ?? "";
      // document.getElementById("namaLengkap").innerText =
      //   data.nama_lengkap ?? "";
      // document.getElementById("petaniFoto").src =
      //   "admin/uploads/petani/" +
      //   (data.foto_profil_petani || "assets/image/petani_placeholder.jpg");
      // document.getElementById("jenisKelamin").innerText =
      //   data.jenis_kelamin == "L"
      //     ? "Laki-laki"
      //     : data.jenis_kelamin == "P"
      //       ? "Perempuan"
      //       : "";
      // document.getElementById("umur").innerText = data.tanggal_lahir
      //   ? new Date().getFullYear() -
      //     new Date(data.tanggal_lahir).getFullYear() +
      //     " tahun"
      //   : "";
      // document.getElementById("statusPetani").innerText =
      //   data.status_petani == "aktif"
      //     ? "Aktif"
      //     : data.status_petani == "nonaktif"
      //       ? "Non-Aktif"
      //       : "";
      // document.getElementById("alamatPetani").innerText =
      //   data.alamat_petani ?? "";
      // document.getElementById("desaPetani").innerText = data.desa_petani ?? "";
      // document.getElementById("kecamatanPetani").innerText =
      //   data.kecamatan_petani ?? "";
      // document.getElementById("kabupatenPetani").innerText =
      //   data.kabupaten_petani ?? "";

      setTranslatedText("namaPanggilan", data.nama_panggilan ?? "");
      setTranslatedText("namaLengkap", data.nama_lengkap ?? "");
      document.getElementById("petaniFoto").src =
        "admin/uploads/petani/" + data.foto_profil_petani ||
        "assets/image/petani_placeholder.jpg";
      setTranslatedText(
        "jenisKelamin",
        data.jenis_kelamin == "L"
          ? "Laki-laki"
          : data.jenis_kelamin == "P"
            ? "Perempuan"
            : "",
      );
      setTranslatedText(
        "umur",
        data.tanggal_lahir
          ? new Date().getFullYear() -
              new Date(data.tanggal_lahir).getFullYear() +
              " tahun"
          : "",
      );
      setTranslatedText(
        "statusPetani",
        data.status_petani == "aktif"
          ? "Aktif"
          : data.status_petani == "nonaktif"
            ? "Non-Aktif"
            : "",
      );
      setTranslatedText("alamatPetani", data.alamat_petani ?? "");
      setTranslatedText("desaPetani", data.desa_petani ?? "");
      setTranslatedText("kecamatanPetani", data.kecamatan_petani ?? "");
      setTranslatedText("kabupatenPetani", data.kabupaten_petani ?? "");

      document.getElementById("kelompokPetaniTableBody").innerHTML = `
        <tr>
          <td colspan="7" style="text-align:center;">Loading...</td>
        </tr>
      `;

      // ambil data petani
      fetch(`api/list_kelompok_petani.php?id=${data.id}`)
        .then((res) => res.json())
        .then((resPetani) => {
          console.log(resPetani);
          const tbody = document.getElementById("kelompokPetaniTableBody");
          tbody.innerHTML = "";

          // ambil data dengan aman
          let kelompokPetaniList = [];

          if (Array.isArray(resPetani.data)) {
            kelompokPetaniList = resPetani.data;
          } else if (Array.isArray(resPetani)) {
            kelompokPetaniList = resPetani;
          } else if (resPetani.data && typeof resPetani.data === "object") {
            kelompokPetaniList = [resPetani.data]; // bungkus jadi array
          }

          if (kelompokPetaniList.length === 0) {
            tbody.innerHTML = `
              <tr>
                <td colspan="7" style="text-align:center;">Tidak ada data petani</td>
              </tr>
            `;
            return;
          }

          kelompokPetaniList.forEach((item) => {
            const row = `
              <tr>
                <td>${item.nama_kelompok ?? "-"}</td>
                <td>${item.kategori_kelompok ?? "-"}</td>
                <td>${
                  item.tanggal_gabung
                    ? new Date(item.tanggal_gabung).toLocaleDateString(
                        "id-ID",
                        {
                          day: "numeric",
                          month: "long",
                          year: "numeric",
                          timeZone: "UTC",
                        },
                      )
                    : ""
                }</td>
              </tr>
            `;

            tbody.insertAdjacentHTML("beforeend", row);
          });
        })
        .catch((err) => {
          console.error(err);

          const tbody = document.getElementById("kelompokPetaniTableBody");
          tbody.innerHTML = `
            <tr>
              <td colspan="7" style="text-align:center; color:red;">
                Gagal memuat data petani
              </td>
            </tr>
          `;
        });

      // document.getElementById("namaKaleka").innerText = data.nama_kaleka ?? "";
      // document.getElementById("namaLahan").innerText = data.nama_lahan ?? "";
      // document.getElementById("legalitasLahan").innerText =
      //   data.legalitas_lahan ?? "";
      // document.getElementById("luasHaTanah").innerText =
      //   data.luas_ha_tanah + " Ha" ?? "";
      // document.getElementById("sejarahTanah").innerText =
      //   data.sejarah_tanah ?? "";
      // document.getElementById("alamatLokasiTanah").innerText =
      //   data.alamat_lokasi_tanah ?? "";
      // document.getElementById("keteranganTanah").innerText =
      //   data.keterangan_tanah ?? "";
      // document.getElementById("sudahValidasiTanah").innerText =
      //   data.sudah_validasi_tanah == 1 ? "Ya" : "Tidak";
      // document.getElementById("tanggalValidasiTanah").innerText =
      //   data.tanggal_validasi_tanah
      //     ? new Date(data.tanggal_validasi_tanah).toLocaleDateString("id-ID", {
      //         day: "numeric",
      //         month: "long",
      //         year: "numeric",
      //         timeZone: "UTC",
      //       })
      //     : "";

      // document.getElementById("periodePengecekanPerairan").innerText =
      //   data.periode_pengecekan_perairan
      //     ? new Date(data.periode_pengecekan_perairan).toLocaleDateString(
      //         "id-ID",
      //         {
      //           day: "numeric",
      //           month: "long",
      //           year: "numeric",
      //           timeZone: "UTC",
      //         },
      //       )
      //     : "";
      // document.getElementById("warnaAirPerairan").innerText =
      //   data.warna_air_perairan ?? "";
      // document.getElementById("jenisPalungPerairan").innerText =
      //   data.jenis_palung_perairan ?? "";
      // document.getElementById("kecepatanAliranPerairan").innerText =
      //   data.kecepatan_aliran_perairan ?? "";
      // document.getElementById("kedalamanPerairan").innerText =
      //   data.kedalaman_perairan + "cm" ?? "";
      // document.getElementById("lebarPerairan").innerText =
      //   data.lebar_perairan + "m" ?? "";
      // document.getElementById("debitPerairan").innerText =
      //   data.debit_perairan + "lps" ?? "";
      // document.getElementById("phPerairan").innerText =
      //   data.ph_perairan + "pH" ?? "";
      // document.getElementById("kekeruhanPerairan").innerText =
      //   data.kekeruhan_perairan + "NTU" ?? "";
      // document.getElementById("catatanPerairan").innerText =
      //   data.catatan_perairan ?? "";

      // document.getElementById("periodePengecekanInfrastruktur").innerText =
      //   data.periode_pengecekan_infrastruktur
      //     ? new Date(data.periode_pengecekan_infrastruktur).toLocaleDateString(
      //         "id-ID",
      //         {
      //           day: "numeric",
      //           month: "long",
      //           year: "numeric",
      //           timeZone: "UTC",
      //         },
      //       )
      //     : "";
      // document.getElementById("aksesPerjalananInfrastruktur").innerText =
      //   data.akses_perjalanan_infrastruktur ?? "";
      // document.getElementById("kondisiJalanInfrastruktur").innerText =
      //   data.kondisi_jalan_infrastruktur ?? "";
      // document.getElementById("jarakKeJalanInfrastruktur").innerText =
      //   data.jarak_ke_jalan_infrastruktur + " km" ?? "";
      // document.getElementById("adaJembatanInfrastruktur").innerText =
      //   data.ada_jembatan_infrastruktur == 1 ? "Ya" : ("Tidak" ?? "");
      // document.getElementById("adaListrikInfrastruktur").innerText =
      //   data.ada_listrik_infrastruktur == 1 ? "Ya" : ("Tidak" ?? "");
      // document.getElementById("adaInternetInfrastruktur").innerText =
      //   data.ada_internet_infrastruktur == 1 ? "Ya" : ("Tidak" ?? "");
      // document.getElementById("sinyalSelulerInfrastruktur").innerText =
      //   data.sinyal_seluler_infrastruktur == "tidak_ada"
      //     ? "Tidak Ada"
      //     : data.sinyal_seluler_infrastruktur == "lemah"
      //       ? "Lemah"
      //       : data.sinyal_seluler_infrastruktur == "sedang"
      //         ? "Sedang"
      //         : data.sinyal_seluler_infrastruktur == "kuat"
      //           ? "Kuat"
      //           : "";
      // document.getElementById("catatanInfrastruktur").innerText =
      //   data.catatan_infrastruktur ?? "";

      // document.getElementById("periodePengecekanLandCover").innerText =
      //   data.periode_pengecekan_land_cover
      //     ? new Date(data.periode_pengecekan_land_cover).toLocaleDateString(
      //         "id-ID",
      //         {
      //           day: "numeric",
      //           month: "long",
      //           year: "numeric",
      //           timeZone: "UTC",
      //         },
      //       )
      //     : "";
      // document.getElementById("kategoriAreaLandCover").innerText =
      //   data.kategori_area_land_cover ?? "";
      // document.getElementById("penggunaanPertanianLandCover").innerText =
      //   data.penggunaan_pertanian_land_cover ?? "";
      // document.getElementById("penggunaanLainnyaLandCover").innerText =
      //   data.penggunaan_lainnya_land_cover ?? "";
      // document.getElementById("persentaseTutupanLandCover").innerText =
      //   data.persentase_tutupan_land_cover + "%" ?? "";
      // document.getElementById("catatanLandCover").innerText =
      //   data.catatan_land_cover ?? "";

      // document.getElementById("periodePengecekanTopografi").innerText =
      //   data.periode_pengecekan_topografi
      //     ? new Date(data.periode_pengecekan_topografi).toLocaleDateString(
      //         "id-ID",
      //         {
      //           day: "numeric",
      //           month: "long",
      //           year: "numeric",
      //           timeZone: "UTC",
      //         },
      //       )
      //     : "";
      // document.getElementById("lanskapTopografi").innerText =
      //   data.lanskap_topografi ?? "";
      // document.getElementById("fiturTambahanTopografi").innerText =
      //   data.fitur_tambahan_topografi ?? "";
      // document.getElementById("elevasiTopografi").innerText =
      //   data.elevasi_topografi + " mdpl" ?? "";
      // document.getElementById("kemiringanTopografi").innerText =
      //   data.kemiringan_topografi + "°" ?? "";
      // document.getElementById("rawanErosiTopografi").innerText =
      //   data.rawan_erosi_topografi == 1 ? "Ya" : ("Tidak" ?? "");
      // document.getElementById("arahLerengTopografi").innerText =
      //   data.arah_lereng_topografi ?? "";
      // document.getElementById("catatanTopografi").innerText =
      //   data.catatan_topografi ?? "";

      // document.getElementById("periodePengecekanPohon").innerText =
      //   data.periode_pengecekan_pohon
      //     ? new Date(data.periode_pengecekan_pohon).toLocaleDateString(
      //         "id-ID",
      //         {
      //           day: "numeric",
      //           month: "long",
      //           year: "numeric",
      //           timeZone: "UTC",
      //         },
      //       )
      //     : "";
      // document.getElementById("jenisPohon").innerText = data.jenis_pohon ?? "";
      // document.getElementById("fungsiPohon").innerText =
      //   data.fungsi_pohon ?? "";
      // document.getElementById("jumlahPohon").innerText =
      //   data.jumlah_pohon ?? "";
      // document.getElementById("diameterRata2CmPohon").innerText =
      //   data.diameter_rata2_cm_pohon + "cm" ?? "";
      // document.getElementById("tinggiRata2MPohon").innerText =
      //   data.tinggi_rata2_m_pohon + "m" ?? "";
      // document.getElementById("kondisiPohon").innerText =
      //   data.kondisi_pohon == "baik"
      //     ? "Baik"
      //     : data.kondisi_pohon == "sedang"
      //       ? "Sedang"
      //       : data.kondisi_pohon == "buruk"
      //         ? "Buruk"
      //         : "";
      // document.getElementById("catatanPohon").innerText =
      //   data.catatan_pohon ?? "";

      setTranslatedText("namaKaleka", data.nama_kaleka ?? "");
      setTranslatedText("namaLahan", data.nama_lahan ?? "");
      setTranslatedText("legalitasLahan", data.legalitas_lahan ?? "");
      setTranslatedText(
        "luasHaTanah",
        data.luas_ha_tanah ? data.luas_ha_tanah + " Ha" : "",
      );
      setTranslatedText("sejarahTanah", data.sejarah_tanah ?? "");
      setTranslatedText("alamatLokasiTanah", data.alamat_lokasi_tanah ?? "");
      setTranslatedText("keteranganTanah", data.keterangan_tanah ?? "");
      setTranslatedText(
        "sudahValidasiTanah",
        data.sudah_validasi_tanah == 1 ? "Ya" : "Tidak",
      );
      setTranslatedText(
        "tanggalValidasiTanah",
        data.tanggal_validasi_tanah
          ? new Date(data.tanggal_validasi_tanah).toLocaleDateString("id-ID", {
              day: "numeric",
              month: "long",
              year: "numeric",
              timeZone: "UTC",
            })
          : "",
      );

      setTranslatedText(
        "periodePengecekanPerairan",
        data.periode_pengecekan_perairan
          ? new Date(data.periode_pengecekan_perairan).toLocaleDateString(
              "id-ID",
              {
                day: "numeric",
                month: "long",
                year: "numeric",
                timeZone: "UTC",
              },
            )
          : "",
      );
      setTranslatedText("warnaAirPerairan", data.warna_air_perairan ?? "");
      setTranslatedText(
        "jenisPalungPerairan",
        data.jenis_palung_perairan ?? "",
      );
      setTranslatedText(
        "kecepatanAliranPerairan",
        data.kecepatan_aliran_perairan ?? "",
      );
      setTranslatedText(
        "kedalamanPerairan",
        data.kedalaman_perairan ? data.kedalaman_perairan + "cm" : "",
      );
      setTranslatedText(
        "lebarPerairan",
        data.lebar_perairan ? data.lebar_perairan + "m" : "",
      );
      setTranslatedText(
        "debitPerairan",
        data.debit_perairan ? data.debit_perairan + "lps" : "",
      );
      setTranslatedText(
        "phPerairan",
        data.ph_perairan ? data.ph_perairan + "pH" : "",
      );
      setTranslatedText(
        "kekeruhanPerairan",
        data.kekeruhan_perairan ? data.kekeruhan_perairan + "NTU" : "",
      );
      setTranslatedText("catatanPerairan", data.catatan_perairan ?? "");

      setTranslatedText(
        "periodePengecekanInfrastruktur",
        data.periode_pengecekan_infrastruktur
          ? new Date(data.periode_pengecekan_infrastruktur).toLocaleDateString(
              "id-ID",
              {
                day: "numeric",
                month: "long",
                year: "numeric",
                timeZone: "UTC",
              },
            )
          : "",
      );
      setTranslatedText(
        "aksesPerjalananInfrastruktur",
        data.akses_perjalanan_infrastruktur ?? "",
      );
      setTranslatedText(
        "kondisiJalanInfrastruktur",
        data.kondisi_jalan_infrastruktur ?? "",
      );
      setTranslatedText(
        "jarakKeJalanInfrastruktur",
        data.jarak_ke_jalan_infrastruktur
          ? data.jarak_ke_jalan_infrastruktur + " km"
          : "",
      );
      setTranslatedText(
        "adaJembatanInfrastruktur",
        data.ada_jembatan_infrastruktur == 1 ? "Ya" : "Tidak",
      );
      setTranslatedText(
        "adaListrikInfrastruktur",
        data.ada_listrik_infrastruktur == 1 ? "Ya" : "Tidak",
      );
      setTranslatedText(
        "adaInternetInfrastruktur",
        data.ada_internet_infrastruktur == 1 ? "Ya" : "Tidak",
      );
      setTranslatedText(
        "sinyalSelulerInfrastruktur",
        data.sinyal_seluler_infrastruktur == "tidak_ada"
          ? "Tidak Ada"
          : data.sinyal_seluler_infrastruktur == "lemah"
            ? "Lemah"
            : data.sinyal_seluler_infrastruktur == "sedang"
              ? "Sedang"
              : data.sinyal_seluler_infrastruktur == "kuat"
                ? "Kuat"
                : "",
      );
      setTranslatedText(
        "catatanInfrastruktur",
        data.catatan_infrastruktur ?? "",
      );

      setTranslatedText(
        "periodePengecekanLandCover",
        data.periode_pengecekan_land_cover
          ? new Date(data.periode_pengecekan_land_cover).toLocaleDateString(
              "id-ID",
              {
                day: "numeric",
                month: "long",
                year: "numeric",
                timeZone: "UTC",
              },
            )
          : "",
      );
      setTranslatedText(
        "kategoriAreaLandCover",
        data.kategori_area_land_cover ?? "",
      );
      setTranslatedText(
        "penggunaanPertanianLandCover",
        data.penggunaan_pertanian_land_cover ?? "",
      );
      setTranslatedText(
        "penggunaanLainnyaLandCover",
        data.penggunaan_lainnya_land_cover ?? "",
      );
      setTranslatedText(
        "persentaseTutupanLandCover",
        data.persentase_tutupan_land_cover
          ? data.persentase_tutupan_land_cover + "%"
          : "",
      );
      setTranslatedText("catatanLandCover", data.catatan_land_cover ?? "");

      setTranslatedText(
        "periodePengecekanTopografi",
        data.periode_pengecekan_topografi
          ? new Date(data.periode_pengecekan_topografi).toLocaleDateString(
              "id-ID",
              {
                day: "numeric",
                month: "long",
                year: "numeric",
                timeZone: "UTC",
              },
            )
          : "",
      );
      setTranslatedText("lanskapTopografi", data.lanskap_topografi ?? "");
      setTranslatedText(
        "fiturTambahanTopografi",
        data.fitur_tambahan_topografi ?? "",
      );
      setTranslatedText(
        "elevasiTopografi",
        data.elevasi_topografi ? data.elevasi_topografi + " mdpl" : "",
      );
      setTranslatedText(
        "kemiringanTopografi",
        data.kemiringan_topografi ? data.kemiringan_topografi + "°" : "",
      );
      setTranslatedText(
        "rawanErosiTopografi",
        data.rawan_erosi_topografi == 1 ? "Ya" : "Tidak",
      );
      setTranslatedText(
        "arahLerengTopografi",
        data.arah_lereng_topografi ?? "",
      );
      setTranslatedText("catatanTopografi", data.catatan_topografi ?? "");

      setTranslatedText(
        "periodePengecekanPohon",
        data.periode_pengecekan_pohon
          ? new Date(data.periode_pengecekan_pohon).toLocaleDateString(
              "id-ID",
              {
                day: "numeric",
                month: "long",
                year: "numeric",
                timeZone: "UTC",
              },
            )
          : "",
      );
      setTranslatedText("jenisPohon", data.jenis_pohon ?? "");
      setTranslatedText("fungsiPohon", data.fungsi_pohon ?? "");
      setTranslatedText("jumlahPohon", data.jumlah_pohon ?? "");
      setTranslatedText(
        "diameterRata2CmPohon",
        data.diameter_rata2_cm_pohon
          ? data.diameter_rata2_cm_pohon + " cm"
          : "",
      );
      setTranslatedText(
        "tinggiRata2MPohon",
        data.tinggi_rata2_m_pohon ? data.tinggi_rata2_m_pohon + " m" : "",
      );
      setTranslatedText(
        "kondisiPohon",
        data.kondisi_pohon == "baik"
          ? "Baik"
          : data.kondisi_pohon == "sedang"
            ? "Sedang"
            : data.kondisi_pohon == "buruk"
              ? "Buruk"
              : "",
      );
      setTranslatedText("catatanPohon", data.catatan_pohon ?? "");

      // document.getElementById("petaniKelompokTableBody").innerHTML = `
      //   <tr>
      //     <td colspan="7" style="text-align:center;">Loading...</td>
      //   </tr>
      // `;

      // ambil data petani
      // fetch(`api/list_petani.php?id=${data.id_kelompok_tani}`)
      //   .then((res) => res.json())
      //   .then((resPetani) => {
      //     console.log(resPetani);
      //     const tbody = document.getElementById("petaniKelompokTableBody");
      //     tbody.innerHTML = "";

      //     // ambil data dengan aman
      //     let petaniList = [];

      //     if (Array.isArray(resPetani.data)) {
      //       petaniList = resPetani.data;
      //     } else if (Array.isArray(resPetani)) {
      //       petaniList = resPetani;
      //     } else if (resPetani.data && typeof resPetani.data === "object") {
      //       petaniList = [resPetani.data]; // bungkus jadi array
      //     }

      //     if (petaniList.length === 0) {
      //       tbody.innerHTML = `
      //         <tr>
      //           <td colspan="7" style="text-align:center;">Tidak ada data petani</td>
      //         </tr>
      //       `;
      //       return;
      //     }

      //     petaniList.forEach((item) => {
      //       const row = `
      //         <tr>
      //           <td>${item.nama_lengkap ?? "-"}</td>
      //           <td>${item.nama_panggilan ?? "-"}</td>
      //           <td>${item.jenis_kelamin === "L" ? "Laki-laki" : "Perempuan"}</td>
      //           <td>${hitungUmur(item.tanggal_lahir)}</td>
      //           <td>${item.status_petani === "aktif" ? "Aktif" : "Nonaktif"}</td>
      //           <td>${item.alamat ?? "-"}</td>
      //           <td>
      //             ${
      //               item.foto
      //                 ? `<img src="uploads/petani/${item.foto}" width="50" height="50" class="img-fluid rounded-circle">`
      //                 : `<img src="assets/image/petani_placeholder.jpg" width="50" height="50" class="img-fluid rounded-circle">`
      //             }
      //           </td>
      //         </tr>
      //       `;

      //       tbody.insertAdjacentHTML("beforeend", row);
      //     });
      //   })
      //   .catch((err) => {
      //     console.error(err);

      //     const tbody = document.getElementById("petaniKelompokTableBody");
      //     tbody.innerHTML = `
      //       <tr>
      //         <td colspan="7" style="text-align:center; color:red;">
      //           Gagal memuat data petani
      //         </td>
      //       </tr>
      //     `;
      //   });
    })
    .catch((err) => {
      console.error(err);

      document.getElementById("loadingDetail").style.display = "none";
      document.getElementById("kalekaModal").style.display = "block";

      document.getElementById("kalekaModal").innerHTML = `
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
loadTotalFarmer();

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

const translations = {
  id: {
    hero_title: "Halo teman Borneologi!",
    hero_subtitle: "Borneologi menyajikan peta lahan interaktif.",
    lihat_peta: "Lihat Peta",

    menu_home: "Beranda",
    menu_map: "Peta",
    text_peta_sebaran: "Peta Sebaran",

    text_total: "Total",
    text_petani: "Petani",
    text_laki_laki: "Laki-Laki",
    text_perempuan: "Perempuan",

    text_keterangan: "Keterangan",
    text_provinsi: "Provinsi",
    text_kabupaten: "Kabupaten",
    text_kecamatan: "Kecamatan",
    text_hutan_adat: "Hutan Adat",
    text_kaleka: "Kaleka",
    text_bank_benih: "Bank Benih",

    text_data_provinsi: "Data Provinsi",
    text_nama_provinsi: "Nama Provinsi",
    text_luas_provinsi: "Luas Provinsi (ha)",
    text_data_kabupaten: "Data Kabupaten",
    text_list_kabupaten: "List Kabupaten",
    text_nama_kabupaten: "Nama Kabupaten",
    text_kode_kabupaten: "Kode Kabupaten",
    text_luas_kabupaten: "Luas Kabupaten (ha)",
    text_data_kecamatan: "Data Kecamatan",
    text_list_kecamatan: "List Kecamatan",
    text_nama_kecamatan: "Nama Kecamatan",
    text_kode_kecamatan: "Kode Kecamatan",
    text_luas_kecamatan: "Luas Kecamatan (ha)",

    text_nama_hutan_adat: "Nama Hutan Adat",
    text_nomor_sk: "Nomor SK",
    text_tanggal_sk: "Tanggal SK",
    text_status_kawasan: "Status Kawasan",
    text_nama_desa: "Nama Desa",
    text_masyarakat_hukum_adat: "Masyarakat Hukum Adat",
    text_nama_masyarakat_hukum_adat: "Nama Masyarakat Hukum Adat",
    text_kategori_masyarakat_hukum_adat: "Kategori Masyarakat Hukum Adat",
    text_tahun_bentuk: "Tahun Bentuk",
    text_status_masyarakat_hukum_adat: "Status Masyarakat Hukum Adat",
    text_total_anggota: "Total Anggota",
    text_alamat_masyarakat_hukum_adat: "Alamat Masyarakat Hukum Adat",
    text_deskripsi: "Deskripsi",
    text_pengurus_masyarakat_hukum_adat: "Pengurus Masyarakat Hukum Adat",
    text_nama_lengkap: "Nama Lengkap",
    text_nama_panggilan: "Nama Panggilan",
    text_jenis_kelamin: "Jenis Kelamin",
    text_umur: "Umur",
    text_status_petani: "Status Petani",
    text_alamat: "Alamat",
    text_foto: "Foto",
    text_tanah: "Tanah",
    text_nama_lahan: "Nama Lahan",
    text_legalitas: "Legalitas",
    text_luas_lahan: "Luas Lahan (ha)",
    text_sejarah_lahan: "Sejarah Lahan",
    text_alamat_lahan: "Alamat Lahan",
    text_keterangan_lahan: "Keterangan Lahan",
    text_sudah_validasi: "Sudah Validasi",
    text_tanggal_validasi: "Tanggal Validasi",
    text_alamat_lokasi_lahan: "Alamat Lokasi Lahan",

    text_petani: "Petani",
    text_data_pribadi: "Data Pribadi",
    text_aktif: "Aktif",
    text_tidak_aktif: "Tidak Aktif",
    text_kelompok_petani: "Kelompok Petani",
    text_kelompok_tani: "Kelompok Tani",
    text_kategori_kelompok: "Kategori Kelompok",
    text_tahun_gabung: "Tahun Gabung",
    text_nama_kaleka: "Nama Kaleka",
    text_perairan_observasi: "Perairan Observasi",
    text_periode_pengecekan: "Periode Pengecekan",
    text_warna_air: "Warna Air",
    text_jenis_palung: "Jenis Palung",
    text_kecepatan_aliran: "Kecepatan Aliran",
    text_kedalaman_air: "Kedalaman Air",
    text_lebar: "Lebar",
    text_debit_air: "Debit Air",
    text_ph: "pH",
    text_kekeruhan: "Kekeruhan",
    text_catatan: "Catatan",
    text_akses_perjalanan: "Akses Perjalanan",
    text_kondisi_jalan: "Kondisi Jalan",
    text_jarak_ke_jalan: "Jarak ke Jalan",
    text_ada_jembatan: "Ada Jembatan",
    text_ada_listrik: "Ada Listrik",
    text_ada_internet: "Ada Internet",
    text_sinyal_seluler: "Sinyal Seluler",
    text_kategori_area: "Kategori Area",
    text_penggunaan_pertanian: "Penggunaan Pertanian",
    text_penggunaan_lainnya: "Penggunaan Lainnya",
    text_persentase_tutupan: "Persentase Tutupan",
    text_lanskap: "Lanskap",
    text_fitur_tambahan: "Fitur Tambahan",
    text_elevasi: "Elevasi (mdpl)",
    text_kemiringan_derajat: "Kemiringan (derajat)",
    text_rawan_erosi: "Rawan Erosi",
    text_arah_lereng: "Arah Lereng",
    text_jenis_pohon: "Jenis Pohon",
    text_fungsi_pohon: "Fungsi Pohon",
    text_jumlah_pohon: "Jumlah Pohon",
    text_diameter_rata_rata: "Diameter Rata-Rata (cm)",
    text_tinggi_rata_rata: "Tinggi Rata-Rata (m)",
    text_kondisi: "Kondisi",
    text_hasil: "Hasil",
  },

  en: {
    hero_title: "Hello Borneology friends!",
    hero_subtitle: "Borneology presents an interactive land map.",
    lihat_peta: "View Map",

    menu_home: "Home",
    menu_map: "Map",
    text_peta_sebaran: "Distribution Map",

    text_total: "Total",
    text_petani: "Farmers",
    text_laki_laki: "Male",
    text_perempuan: "Female",

    text_keterangan: "Description",
    text_provinsi: "Province",
    text_kabupaten: "Regency",
    text_kecamatan: "District",
    text_hutan_adat: "Customary Forest",
    text_kaleka: "Kaleka",
    text_bank_benih: "Seed Bank",

    text_data_provinsi: "Province Data",
    text_nama_provinsi: "Province Name",
    text_luas_provinsi: "Province Area (ha)",
    text_data_kabupaten: "Regency Data",
    text_list_kabupaten: "Regency List",
    text_nama_kabupaten: "Regency Name",
    text_kode_kabupaten: "Regency Code",
    text_luas_kabupaten: "Regency Area (ha)",
    text_data_kecamatan: "District Data",
    text_list_kecamatan: "District List",
    text_nama_kecamatan: "District Name",
    text_kode_kecamatan: "District Code",
    text_luas_kecamatan: "District Area (ha)",

    text_nama_hutan_adat: "Customary Forest Name",
    text_nomor_sk: "SK Number",
    text_tanggal_sk: "SK Date",
    text_status_kawasan: "Area Status",
    text_nama_desa: "Village Name",
    text_masyarakat_hukum_adat: "Customary Law Community",
    text_nama_masyarakat_hukum_adat: "Customary Law Community Name",
    text_kategori_masyarakat_hukum_adat: "Customary Law Community Category",
    text_tahun_bentuk: "Formation Year",
    text_status_masyarakat_hukum_adat: "Customary Law Community Status",
    text_total_anggota: "Total Members",
    text_alamat_masyarakat_hukum_adat: "Customary Law Community Address",
    text_deskripsi: "Description",
    text_pengurus_masyarakat_hukum_adat: "Customary Law Community Management",
    text_nama_lengkap: "Full Name",
    text_nama_panggilan: "Nickname",
    text_jenis_kelamin: "Gender",
    text_umur: "Age",
    text_status_petani: "Farmer Status",
    text_alamat: "Address",
    text_foto: "Photo",
    text_tanah: "Land",
    text_nama_lahan: "Land Name",
    text_legalitas: "Legality",
    text_luas_lahan: "Land Area (ha)",
    text_sejarah_lahan: "Land History",
    text_alamat_lahan: "Land Address",
    text_keterangan_lahan: "Land Description",
    text_sudah_validasi: "Already Validated",
    text_tanggal_validasi: "Validation Date",
    text_alamat_lokasi_lahan: "Land Location Address",

    text_petani: "Farmer",
    text_data_pribadi: "Personal Data",
    text_aktif: "Active",
    text_tidak_aktif: "Inactive",
    text_kelompok_petani: "Farmer Group",
    text_kelompok_tani: "Farmer Group",
    text_kategori_kelompok: "Group Category",
    text_tahun_gabung: "Joining Year",
    text_nama_kaleka: "Kaleka Name",
    text_perairan_observasi: "Observation Waters",
    text_periode_pengecekan: "Checking Period",
    text_warna_air: "Water Color",
    text_jenis_palung: "Trench Type",
    text_kecepatan_aliran: "Flow Speed",
    text_kedalaman_air: "Water Depth",
    text_lebar: "Width",
    text_debit_air: "Water Discharge",
    text_ph: "pH",
    text_kekeruhan: "Turbidity",
    text_catatan: "Notes",
    text_akses_perjalanan: "Travel Access",
    text_kondisi_jalan: "Road Condition",
    text_jarak_ke_jalan: "Distance to Road",
    text_ada_jembatan: "Has Bridge",
    text_ada_listrik: "Has Electricity",
    text_ada_internet: "Has Internet",
    text_sinyal_seluler: "Cellular Signal",
    text_kategori_area: "Area Category",
    text_penggunaan_pertanian: "Agricultural Use",
    text_penggunaan_lainnya: "Other Uses",
    text_persentase_tutupan: "Coverage Percentage",
    text_lanskap: "Landscape",
    text_fitur_tambahan: "Additional Features",
    text_elevasi: "Elevation (m asl)",
    text_kemiringan_derajat: "Slope (degrees)",
    text_rawan_erosi: "Erosion Prone",
    text_arah_lereng: "Slope Direction",
    text_jenis_pohon: "Tree Type",
    text_fungsi_pohon: "Tree Function",
    text_jumlah_pohon: "Number of Trees",
    text_diameter_rata_rata: "Average Diameter (cm)",
    text_tinggi_rata_rata: "Average Height (m)",
    text_kondisi: "Condition",
    text_hasil: "Result",
  },
};

const flagConfig = {
  id: "images/flags/id.png",
  en: "images/flags/en.png",
};

// FUNCTION GANTI BAHASA
function setLanguage(lang) {
  // translate text
  document.querySelectorAll("[data-lang]").forEach((el) => {
    const key = el.getAttribute("data-lang");
    if (translations[lang][key]) {
      el.innerHTML = translations[lang][key];
    }
  });

  // update semua bendera
  document.querySelectorAll(".current-flag").forEach((flag) => {
    flag.src = flagConfig[lang];
  });
  // simpan bahasa
  localStorage.setItem("language", lang);
}

// EVENT CLICK DROPDOWN
document.querySelectorAll(".language-option").forEach((item) => {
  item.addEventListener("click", function (e) {
    e.preventDefault();
    const lang = this.dataset.lang;
    setLanguage(lang);
  });
});

// LOAD SAAT PAGE DIBUKA
const savedLanguage = localStorage.getItem("language") || "id";

setLanguage(savedLanguage);

async function autoTranslate(text, targetLang = "en") {
  if (targetLang === "id") {
    return text;
  }
  try {
    const response = await fetch(
      "https://translate.googleapis.com/translate_a/single?client=gtx&sl=id&tl=" +
        targetLang +
        "&dt=t&q=" +
        encodeURIComponent(text),
    );
    const data = await response.json();
    return data[0].map((item) => item[0]).join("");
  } catch (error) {
    console.error("Translation error:", error);
    return text;
  }
}

async function setTranslatedText(elementId, text) {
  const lang = localStorage.getItem("language");
  const translated = await autoTranslate(text, lang);
  document.getElementById(elementId).innerText = translated;
}
