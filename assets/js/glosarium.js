const GLOSARIUM_API = 'api/glosarium.php';

let glossaryData = [];
let activeLetter = 'all';
let activeCategory = 'all';
let searchKeyword = '';

/*
=========================================
LOAD DATA DARI API
=========================================
*/
async function loadGlossary() {
    const container =
        document.getElementById('glossaryList');
    try {
        const response =
            await fetch(GLOSARIUM_API);
        if (!response.ok) {
            throw new Error(
                'Gagal mengambil data API.'
            );
        }

        const result =
            await response.json();
        if (!result.success) {
            throw new Error(
                result.message ||
                'Gagal mengambil data glosarium.'
            );
        }
        glossaryData =
            Array.isArray(result.data)
                ? result.data
                : [];
        /*
        =========================================
        TOTAL ISTILAH DARI API
        =========================================
        */
        updateTotalGlossary();

        /*
        =========================================
        BUAT FILTER KATEGORI DARI API
        =========================================
        */
        renderCategoryFilters();

        /*
        =========================================
        SIDEBAR KATEGORI DARI API
        =========================================
        */
        renderSidebarCategories();

        /*
        =========================================
        RENDER DATA
        =========================================
        */
        renderGlossary();
    } catch (error) {
        console.error(error);
        container.innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat data glosarium.
                </div>
            `;
    }
}

/*
=========================================
NORMALIZE TEXT
=========================================
Digunakan agar:
Kaleka
kaleka
KALEKA
dianggap sama.
*/
function normalizeText(text) {
    return String(text || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

/*
=========================================
FUZZY SEARCH
=========================================
 
Pencarian:
- tidak membedakan huruf besar/kecil
- mendukung sebagian kata
- mendukung kata yang hampir sama
 
Contoh:
 
"kal"    -> Kaleka
"kalek"  -> Kaleka
"KALEKA" -> Kaleka
"kaleka" -> Kaleka
 
Pencarian hanya dilakukan terhadap:
- istilah
- istilah_lain
=========================================
*/
function fuzzyMatch(keyword, text) {
    keyword = normalizeText(keyword);
    text = normalizeText(text);

    if (!keyword || !text) {
        return false;
    }

    /*
    EXACT / CONTAINS
    */
    if (text.includes(keyword)) {
        return true;
    }

    /*
    Pisahkan menjadi kata
    */
    const words =
        text.split(/\s+/).filter(Boolean);

    /*
    Cek setiap kata
    */
    for (const word of words) {
        /*
        Jika keyword merupakan bagian dari kata
        */
        if (word.includes(keyword)) {
            return true;
        }

        /*
        Levenshtein Distance
        */
        const distance =
            levenshteinDistance(
                keyword,
                word
            );

        /*
        Toleransi kesalahan:
        1-4 karakter  = 1
        5-7 karakter  = 2
        8+ karakter   = 3
        */
        let tolerance = 1;
        if (keyword.length >= 5) {
            tolerance = 2;
        }
        if (keyword.length >= 8) {
            tolerance = 3;
        }
        if (distance <= tolerance) {
            return true;
        }
    }
    return false;
}

/*
=========================================
LEVENSHTEIN DISTANCE
=========================================
*/
function levenshteinDistance(a, b) {
    const matrix = [];

    for (let i = 0; i <= b.length; i++) {
        matrix[i] = [i];
    }

    for (let j = 0; j <= a.length; j++) {
        matrix[0][j] = j;
    }

    for (let i = 1; i <= b.length; i++) {
        for (let j = 1; j <= a.length; j++) {
            if (b.charAt(i - 1) === a.charAt(j - 1)) {
                matrix[i][j] =
                    matrix[i - 1][j - 1];
            } else {
                matrix[i][j] =
                    Math.min(
                        matrix[i - 1][j - 1] + 1,
                        matrix[i][j - 1] + 1,
                        matrix[i - 1][j] + 1
                    );
            }
        }
    }
    return matrix[b.length][a.length];
}

/*
=========================================
CEK SEARCH
=========================================
*/
function matchesSearch(item) {
    if (!searchKeyword) {
        return true;
    }

    /*
    HANYA:
    - istilah
    - istilah_lain
    */
    const istilah =
        item.istilah || '';
    const istilahLain =
        item.istilah_lain || '';
    return (
        fuzzyMatch(
            searchKeyword,
            istilah
        ) ||
        fuzzyMatch(
            searchKeyword,
            istilahLain
        )
    );
}

/*
=========================================
RENDER GLOSARIUM
=========================================
*/
function renderGlossary() {
    const container =
        document.getElementById('glossaryList');
    const noResult =
        document.getElementById('glossaryNoResult');

    let data =
        [...glossaryData];

    /*
    =========================================
    SEARCH
    =========================================
    */
    data =
        data.filter(function (item) {
            return matchesSearch(item);
        });

    /*
    =========================================
    FILTER HURUF
    =========================================
    */
    if (activeLetter !== 'all') {
        data =
            data.filter(function (item) {
                const istilah =
                    normalizeText(
                        item.istilah
                    );
                return istilah
                    .startsWith(
                        normalizeText(
                            activeLetter
                        )
                    );
            });
    }

    /*
    =========================================
    FILTER KATEGORI
    =========================================
    */
    if (activeCategory !== 'all') {
        data =
            data.filter(function (item) {
                return normalizeText(
                    item.kategori
                ) === normalizeText(
                    activeCategory
                );
            });
    }

    /*
    =========================================
    URUTKAN ALFABET
    =========================================
    */
    data.sort(function (a, b) {
        return (
            a.istilah || ''
        ).localeCompare(
            b.istilah || '',
            'id',
            {
                sensitivity: 'base'
            }
        );
    });

    /*
    =========================================
    TIDAK ADA DATA
    =========================================
    */
    if (data.length === 0) {
        container.innerHTML = '';
        noResult.style.display =
            'block';
        return;
    }

    noResult.style.display =
        'none';

    /*
    =========================================
    RENDER CARD
    =========================================
    */
    container.innerHTML =
        data.map(function (item) {
            return createGlossaryCard(item);
        }).join('');
}

/*
=========================================
CREATE CARD
=========================================
*/
function createGlossaryCard(item) {
    const id =
        Number(item.id);
    const istilah =
        escapeHtml(
            item.istilah || '-'
        );
    const istilahLain =
        item.istilah_lain
            ? escapeHtml(
                item.istilah_lain
            )
            : '';
    const kategori =
        escapeHtml(
            item.kategori || 'Umum'
        );
    const definisi =
        escapeHtml(
            item.definisi_singkat || ''
        );
    const pengucapan =
        item.pengucapan
            ? escapeHtml(
                item.pengucapan
            )
            : '';
    return `
            <article class="glossary-card">
                <div class="glossary-card-body">
                    <div class="glossary-card-top">
                        <span class="glossary-category">
                            ${kategori}
                        </span>
                        ${pengucapan
            ? `
                                    <span class="glossary-pronunciation">
                                        /${pengucapan}/
                                    </span>
                                `
            : ''
        }
                    </div>
                    <h4 class="glossary-term">
                        ${istilah}
                    </h4>
                    ${istilahLain
            ? `
                                <div class="glossary-other-term">
                                    ${istilahLain}
                                </div>
                            `
            : ''
        }
                    <p class="glossary-definition">
                        ${definisi}
                    </p>
                    <button
                        type="button"
                        class="glossary-read-more"
                        onclick="openGlossary(${id})">
                        Baca Selengkapnya
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </article>
        `;
}

/*
=========================================
TOTAL ISTILAH
=========================================
*/
function updateTotalGlossary() {
    const totalElement =
        document.querySelector(
            '.glossary-total'
        );
    if (!totalElement) {
        return;
    }
    const total =
        glossaryData.length;
    totalElement.textContent =
        `${total} istilah`;
}

/*
=========================================
GROUP KATEGORI DARI API
=========================================
*/
function getGroupedCategories() {
    const categories = {};
    glossaryData.forEach(function (item) {
        const rawCategory =
            String(
                item.kategori || ''
            ).trim();
        if (!rawCategory) {
            return;
        }

        /*
        Gunakan nama asli kategori
        sebagai label.
        */
        if (!categories[rawCategory]) {
            categories[rawCategory] = 0;
        }
        categories[rawCategory]++;
    });
    return categories;
}

/*
=========================================
RENDER FILTER KATEGORI
=========================================
*/
function renderCategoryFilters() {
    const filterRow =
        document.querySelector(
            '.glossary-filter-row'
        );
    if (!filterRow) {
        return;
    }
    const categories =
        getGroupedCategories();
    let html = `
            <span class="glossary-filter-label">
                Kategori:
            </span>
            <button
                type="button"
                class="glossary-filter active"
                data-category="all">
                Semua
            </button>
        `;

    Object.keys(categories)
        .sort(function (a, b) {
            return a.localeCompare(
                b,
                'id',
                {
                    sensitivity: 'base'
                }
            );
        })
        .forEach(function (category) {
            html += `
                    <button
                        type="button"
                        class="glossary-filter"
                        data-category="${escapeHtml(category)}">
                        ${escapeHtml(category)}
                    </button>
                `;
        });

    filterRow.innerHTML =
        html;

    /*
    Event tombol kategori
    */
    filterRow
        .querySelectorAll(
            '.glossary-filter'
        )
        .forEach(function (button) {
            button.addEventListener(
                'click',
                function () {
                    filterRow
                        .querySelectorAll(
                            '.glossary-filter'
                        )
                        .forEach(function (item) {
                            item.classList.remove(
                                'active'
                            );
                        });
                    this.classList.add(
                        'active'
                    );
                    activeCategory =
                        this.dataset.category ||
                        'all';
                    renderGlossary();
                }
            );
        });
}

/*
=========================================
RENDER SIDEBAR CATEGORY
=========================================
*/
function renderSidebarCategories() {
    const list =
        document.querySelector(
            '.glossary-category-list'
        );
    if (!list) {
        return;
    }
    const categories =
        getGroupedCategories();
    let html = '';
    Object.keys(categories)
        .sort(function (a, b) {
            return a.localeCompare(
                b,
                'id',
                {
                    sensitivity: 'base'
                }
            );
        })
        .forEach(function (category) {
            const count =
                categories[category];
            html += `
                    <li>
                        <a
                            href="javascript:void(0);"
                            class="glossary-sidebar-category"
                            data-category="${escapeHtml(category)}">
                            <span>
                                ${escapeHtml(category)}
                            </span>
                            <span>
                                ${count}
                            </span>
                        </a>
                    </li>
                `;
        });

    if (!html) {
        html = `
                <li>
                    <span
                        style="
                            display:block;
                            padding:10px 0;
                            color:#999;
                        ">
                        Belum ada kategori
                    </span>
                </li>
            `;
    }

    list.innerHTML =
        html;

    /*
    =========================================
    SIDEBAR CATEGORY CLICK
    =========================================
    */
    list
        .querySelectorAll(
            '.glossary-sidebar-category'
        )
        .forEach(function (link) {
            link.addEventListener(
                'click',
                function (e) {
                    e.preventDefault();
                    const category =
                        this.dataset.category ||
                        'all';
                    activeCategory =
                        category;

                    /*
                    Sinkronkan filter
                    kategori di atas
                    */
                    document
                        .querySelectorAll(
                            '.glossary-filter'
                        )
                        .forEach(
                            function (button) {
                                button.classList.toggle(
                                    'active',
                                    normalizeText(
                                        button.dataset.category
                                    ) ===
                                    normalizeText(
                                        category
                                    )
                                );
                            }
                        );
                    renderGlossary();

                    /*
                    Scroll ke daftar
                    */
                    const section =
                        document.querySelector(
                            '.glossary-section'
                        );
                    if (section) {
                        section.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            );
        });
}

/*
=========================================
SEARCH EVENT
=========================================
*/
function setupSearch() {
    const searchInput =
        document.getElementById(
            'glossarySearch'
        );
    const searchButton =
        document.getElementById(
            'glossarySearchButton'
        );
    if (!searchInput) {
        return;
    }

    /*
    Saat mengetik
    */
    searchInput.addEventListener(
        'input',
        function () {
            searchKeyword =
                this.value.trim();
            renderGlossary();
        }
    );

    /*
    Tombol Cari
    */
    if (searchButton) {
        searchButton.addEventListener(
            'click',
            function () {
                searchKeyword =
                    searchInput.value.trim();
                renderGlossary();
            }
        );
    }
}

/*
=========================================
ALPHABET FILTER
=========================================
*/
function setupAlphabet() {
    document.addEventListener(
        'click',
        function (e) {
            const button =
                e.target.closest(
                    '.glossary-letter'
                );
            if (!button) {
                return;
            }

            /*
            Jangan proses tombol lain
            */
            if (
                !button.dataset.letter
            ) {
                return;
            }

            document
                .querySelectorAll(
                    '.glossary-letter'
                )
                .forEach(
                    function (item) {
                        item.classList.remove(
                            'active'
                        );
                    }
                );

            button.classList.add(
                'active'
            );

            activeLetter =
                button.dataset.letter ||
                'all';
            renderGlossary();
        }
    );
}

/*
=========================================
OPEN DETAIL MODAL
=========================================
*/
async function openGlossary(id) {
    const modalElement =
        document.getElementById(
            'glossaryDetailModal'
        );
    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );
    const title =
        document.getElementById(
            'glossaryModalTitle'
        );
    const body =
        document.getElementById(
            'glossaryModalBody'
        );
    title.textContent =
        'Detail Glosarium';
    body.innerHTML = `
            <div class="text-center py-5">
                <div
                    class="spinner-border"
                    role="status">
                </div>
                <p class="mt-3 mb-0">
                    Memuat informasi...
                </p>
            </div>
        `;

    modal.show();
    try {
        const response =
            await fetch(
                `${GLOSARIUM_API}?id=${encodeURIComponent(id)}`
            );
        if (!response.ok) {
            throw new Error(
                'Data tidak ditemukan.'
            );
        }
        const result =
            await response.json();
        if (
            !result.success ||
            !result.data
        ) {
            throw new Error(
                result.message ||
                'Data tidak ditemukan.'
            );
        }

        renderGlossaryDetail(
            result.data
        );
    } catch (error) {
        console.error(error);
        body.innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat detail istilah.
                </div>
            `;
    }
}

/*
=========================================
RENDER DETAIL MODAL
=========================================
*/
function renderGlossaryDetail(item) {
    const title =
        document.getElementById(
            'glossaryModalTitle'
        );
    const body =
        document.getElementById(
            'glossaryModalBody'
        );
    title.textContent =
        item.istilah ||
        'Detail Glosarium';

    /*
    =========================================
    GAMBAR
    =========================================
    */
    let imageHtml = '';
    if (item.gambar) {
        imageHtml = `
                <div
                    class="glossary-detail-image-wrap">
                    <img
                        src="${getImageUrl(item.gambar)}"
                        alt="${escapeHtml(item.istilah || '')}"
                        class="glossary-detail-image"
                        onerror="
                            this.parentElement.style.display='none';
                        ">
                </div>
            `;
    }

    /*
    =========================================
    SUMBER
    =========================================
    */
    let sourceHtml = '';
    if (item.sumber) {
        sourceHtml = `
                <div
                    class="glossary-detail-source">
                    <strong>
                        <i class="bi bi-book"></i>
                        Sumber
                    </strong>
                    <p>
                        ${escapeHtml(item.sumber)}
                    </p>
                </div>
            `;
    }

    body.innerHTML = `
            ${imageHtml}
            <div class="glossary-detail-content">
                <div
                    class="glossary-detail-meta">
                    ${item.kategori
            ? `
                                <span
                                    class="glossary-category">
                                    ${escapeHtml(
                item.kategori
            )}
                                </span>
                            `
            : ''
        }
                    ${item.bahasa_asal
            ? `
                                <span>
                                    <strong>
                                        Bahasa:
                                    </strong>
                                    ${escapeHtml(
                item.bahasa_asal
            )}
                                </span>
                            `
            : ''
        }
                    ${item.pengucapan
            ? `
                                <span>
                                    <strong>
                                        Pengucapan:
                                    </strong>
                                    /${escapeHtml(
                item.pengucapan
            )}/
                                </span>
                            `
            : ''
        }
                </div>
                ${item.istilah_lain
            ? `
                            <div class="mb-4">
                                <small
                                    class="text-muted">
                                    Istilah lain
                                </small>
                                <div>
                                    ${escapeHtml(
                item.istilah_lain
            )}
                                </div>
                            </div>
                        `
            : ''
        }

                ${item.definisi_singkat
            ? `
                            <div
                                class="glossary-detail-section">
                                <h5>
                                    Definisi
                                </h5>
                                <p>
                                    ${escapeHtml(
                item.definisi_singkat
            )}
                                </p>
                            </div>
                        `
            : ''
        }

                ${item.penjelasan_lengkap
            ? `
                            <div
                                class="glossary-detail-section">
                                <h5>
                                    Penjelasan
                                </h5>
                                <div
                                    class="glossary-full-description">
                                    ${formatDescription(
                item.penjelasan_lengkap
            )}
                                </div>
                            </div>
                        `
            : ''
        }
                ${sourceHtml}
            </div>
        `;
}

/*
=========================================
IMAGE URL
=========================================
*/
function getImageUrl(image) {
    if (!image) {
        return '';
    }
    if (
        image.startsWith('http://') ||
        image.startsWith('https://') ||
        image.startsWith('/')
    ) {
        return image;
    }
    return `admin/uploads/glosarium/${encodeURIComponent(image)}`;
}

/*
=========================================
FORMAT DESCRIPTION
=========================================
*/
function formatDescription(text) {
    if (!text) {
        return '';
    }
    return escapeHtml(text)
        .replace(
            /\r\n/g,
            '<br>'
        )
        .replace(
            /\n/g,
            '<br>'
        );
}

/*
=========================================
ESCAPE HTML
=========================================
*/
function escapeHtml(value) {
    return String(value)
        .replace(
            /&/g,
            '&amp;'
        )
        .replace(
            /</g,
            '&lt;'
        )
        .replace(
            />/g,
            '&gt;'
        )
        .replace(
            /"/g,
            '&quot;'
        )
        .replace(
            /'/g,
            '&#039;'
        );
}

/*
=========================================
INIT
=========================================
*/
document.addEventListener(
    'DOMContentLoaded',
    function () {
        setupSearch();
        setupAlphabet();
        loadGlossary();
    }
);