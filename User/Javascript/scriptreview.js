document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".stars span");
    const ratingMessage = document.querySelector(".rating-message");
    const tagsContainer = document.getElementById("tags-container");
    const submitButton = document.getElementById("submit-btn");
    const reviewForm = document.getElementById("review-form");
    const receiptInput = document.getElementById("receipt_id");

    let selectedTags = {};
    let rating = 0;

    const tagIdMapping = {
        tag_food: "tag_food_id",
        tag_clean: "tag_clean_id",
        tag_price: "tag_price_id",
        tag_service: "tag_service_id",
        tag_other: "tag_other_id"
    };

    // ฟังก์ชันการคลิกดาวเพื่อเลือก rating
    stars.forEach(star => {
        star.addEventListener('click', function () {
            rating = parseInt(star.getAttribute('data-value'));
            ratingMessage.textContent = `คุณให้คะแนน ${rating} ดาว`;

            // ล้างคลาส selected จากดาวทุกดวง
            stars.forEach(s => s.classList.remove("selected"));

            // ใส่คลาส selected ตามจำนวนดาวที่เลือก
            for (let i = 0; i < rating; i++) {
                stars[i].classList.add("selected");
            }

            // ดึงแท็กใหม่จาก API
            fetch(`review.php?rating=${rating}`)
                .then(response => response.json())
                .then(data => {
                    tagsContainer.innerHTML = "";
                    selectedTags = {};
                    if (data.tags) {
                        Object.keys(data.tags).forEach(tagType => {
                            const tag = data.tags[tagType];
                            if (tag) {
                                const tagElement = document.createElement("div");
                                tagElement.className = "tag";
                                tagElement.textContent = tag.tag_name;
                                tagElement.dataset.type = tagType;
                                tagElement.dataset.id = tag[tagIdMapping[tagType]];
                                tagElement.addEventListener("click", function () {
                                    if (this.classList.contains("selected")) {
                                        delete selectedTags[this.dataset.type];
                                        this.classList.remove("selected");
                                    } else {
                                        selectedTags[this.dataset.type] = this.dataset.id;
                                        this.classList.add("selected");
                                    }
                                });
                                tagsContainer.appendChild(tagElement);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error("เกิดข้อผิดพลาดในการดึงแท็ก:", error);
                });
        });
    });

    // เมื่อคลิกปุ่ม Submit
    submitButton.addEventListener("click", function (event) {
        event.preventDefault();

        const reviewText = document.querySelector('textarea[name="review"]').value;
        if (reviewText.trim() === '') {
            alert('กรุณากรอกรีวิว');
            return;
        }

        // ตรวจสอบ receipt_id
        if (!receiptInput || !receiptInput.value.trim()) {
            const urlReceiptId = new URLSearchParams(window.location.search).get("receipt_id");
            if (urlReceiptId) {
                receiptInput.value = urlReceiptId;
            } else {
                alert("❌ ไม่พบ receipt_id!");
                return;
            }
        }

        // เติมค่า tag ลง input hidden
        document.querySelector('input[name="tag_food_id"]').value = selectedTags["tag_food"] || "";
        document.querySelector('input[name="tag_clean_id"]').value = selectedTags["tag_clean"] || "";
        document.querySelector('input[name="tag_price_id"]').value = selectedTags["tag_price"] || "";
        document.querySelector('input[name="tag_service_id"]').value = selectedTags["tag_service"] || "";
        document.querySelector('input[name="tag_other_id"]').value = selectedTags["tag_other"] || "";

        // เติมค่า rating ลงใน hidden input
        document.querySelector('input[name="rating"]').value = rating;

        const formData = new FormData(reviewForm);
        formData.append('rating', rating);
        formData.append('receipt_id', receiptInput.value);

        fetch('review.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log("Response from server:", data);
                window.location.href = 'thankyou.php';
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
