const API_BASE_URL = "https://school.teraren.com";
const PAGE_SIZE = 50;
const API_KEY = "dj00aiZpPXpGOWVkeEd2b0w0QyZzPWNvbnN1bWVyc2VjcmV0Jng9M2M-"; // YOLP API キー

// 条件に一致する学校をフィルタリング
function filterSchools(school) {
    const isPrefecturenumberMatch = [8, 12, 13].includes(Number(school.prefecture_number));
    const isSchoolTypeMatch = school.school_type === "C1"; // 中高一貫校
    const isEstablishmentCategoryMatch = school.establishment_category === 3; // 私立

    console.log(`
      学校名: ${school.name},
      都道府県コード: ${school.prefecture_number},
      都道府県一致: ${isPrefecturenumberMatch},
      中高一貫校: ${isSchoolTypeMatch},
      設立区分一致: ${isEstablishmentCategoryMatch}
    `);

    return isPrefecturenumberMatch && isSchoolTypeMatch && isEstablishmentCategoryMatch;
}

// 全データを取得して条件に一致する学校を抽出
async function getAllFilteredSchools() {
    let page = 1;
    let hasMoreData = true;
    const allFilteredSchools = [];

    try {
        while (hasMoreData) {
            const response = await fetch(`${API_BASE_URL}/schools.json?page=${page}&limit=${PAGE_SIZE}`);
            if (!response.ok) throw new Error(`データ取得エラー: ${response.statusText}`);

            const schools = await response.json();
            console.log(`ページ ${page} の取得データ:`, schools);

            const filteredSchools = schools.filter(filterSchools);
            allFilteredSchools.push(...filteredSchools);

            hasMoreData = schools.length === PAGE_SIZE;
            page++;
        }

        return allFilteredSchools;
    } catch (error) {
        console.error("エラーが発生しました:", error);
        alert("学校データの取得中にエラーが発生しました。");
        return [];
    }
}

// テーブルにデータを表示
function populateTable(schools) {
    const tbody = document.querySelector("#schoolTable tbody");
    tbody.innerHTML = ""; // テーブルをクリア

    if (schools.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4">条件に一致する学校がありません。</td></tr>`;
        return;
    }

    schools.forEach((school, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><input type="checkbox" data-index="${index}"></td>
            <td>${school.name}</td>
            <td>${school.location}</td>
            <td>${school.establishment_category === 3 ? "私立" : "その他"}</td>
        `;
        tbody.appendChild(row);
    });
}

// 地図を初期化
function initializeMap(lat, lng) {
    const map = new Y.Map("map");
    map.drawMap(new Y.LatLng(lat, lng), 14, Y.LayerSetId.NORMAL);

    const marker = new Y.Marker(new Y.LatLng(lat, lng));
    map.addFeature(marker);
}

// ジオコーダAPIで住所から緯度経度を取得し地図を表示
async function displayMapForAddress(address) {
    const geocodeUrl = `https://map.yahooapis.jp/geocode/V1/geoCoder?appid=${API_KEY}&query=${encodeURIComponent(address)}&output=json`;
    const response = await fetch(geocodeUrl);
    if (!response.ok) throw new Error(`ジオコーダAPIエラー: ${response.statusText}`);

    const data = await response.json();
    if (data.Feature && data.Feature.length > 0) {
        const coords = data.Feature[0].Geometry.Coordinates.split(",");
        initializeMap(parseFloat(coords[1]), parseFloat(coords[0]));
    } else {
        alert("指定された住所の場所が見つかりませんでした。");
    }
}

// 学校検索ボタンの動作
document.querySelector("#schoolSelect").addEventListener("click", async () => {
    const schools = await getAllFilteredSchools();
    populateTable(schools);
    alert("検索結果を表示しました。");
});

// 地図表示ボタンの動作
document.querySelector("#showMap").addEventListener("click", async () => {
    const tbody = document.querySelector("#schoolTable tbody");
    const checkboxes = tbody.querySelectorAll("input[type='checkbox']:checked");

    if (checkboxes.length === 0) {
        alert("学校を選択してください。");
        return;
    }

    for (const checkbox of checkboxes) {
        const index = checkbox.dataset.index;
        const row = tbody.rows[index];
        const address = row.cells[2].textContent;

        await displayMapForAddress(address);
    }
});
