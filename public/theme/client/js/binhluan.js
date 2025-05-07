//load binh luan
let comments = [];
let currentPage = 0;
const perPage = 3;
function fetchComments() {
    fetch(`/movies/${movieId}/comments`)
        .then(response => response.json())
        .then(data => {
            comments = data;
            if (comments.length > perPage) {
                document.getElementById('prev').style.visibility = 'visible';
                document.getElementById('next').style.visibility = 'visible';
            } else {
                document.getElementById('prev').style.visibility = 'hidden';
                document.getElementById('next').style.visibility = 'hidden';
            }
            showComments();
        })
        .catch(error => console.error('Lỗi khi tải bình luận:', error));
}

function showComments() {
    const start = currentPage * perPage;
    const selectedComments = comments.slice(start, start + perPage);

    let html = '';

    selectedComments.forEach(comment => {
        html += `
            <div class="review">
                <div class="review-header">
                    <span class="reviewer-name">${comment.user.name}</span>
                    <div class="review-rating">
                        <fieldset class="rating">
                            <h5>Xếp hạng: <b>${comment.rating} Điểm</b> </h5>

                            <input type="radio" id="star5_${comment.id}" name="rating_${comment.id}" value="10" ${comment.rating === 10 ? 'checked' : ''}/>
                            <label for="star5_${comment.id}" class="full" id="ratingDisable"></label>

                            <input type="radio" id="star4half_${comment.id}" name="rating_${comment.id}" value="9" ${comment.rating === 9 ? 'checked' : ''}/>
                            <label for="star4half_${comment.id}" class="half" id="ratingDisable"></label>

                            <input type="radio" id="star4_${comment.id}" name="rating_${comment.id}" value="8" ${comment.rating === 8 ? 'checked' : ''}/>
                            <label for="star4_${comment.id}" class="full" id="ratingDisable"></label>

                            <input type="radio" id="star3half_${comment.id}" name="rating_${comment.id}" value="7" ${comment.rating === 7 ? 'checked' : ''}/>
                            <label for="star3half_${comment.id}" class="half" id="ratingDisable"></label>

                            <input type="radio" id="star3_${comment.id}" name="rating_${comment.id}" value="6" ${comment.rating === 6 ? 'checked' : ''}/>
                            <label for="star3_${comment.id}" class="full" id="ratingDisable"></label>

                            <input type="radio" id="star2half_${comment.id}" name="rating_${comment.id}" value="5" ${comment.rating === 5 ? 'checked' : ''}/>
                            <label for="star2half_${comment.id}" class="half" id="ratingDisable"></label>

                            <input type="radio" id="star2_${comment.id}" name="rating_${comment.id}" value="4" ${comment.rating === 4 ? 'checked' : ''}/>
                            <label for="star2_${comment.id}" class="full" id="ratingDisable"></label>

                            <input type="radio" id="star1half_${comment.id}" name="rating_${comment.id}" value="3" ${comment.rating === 3 ? 'checked' : ''}/>
                            <label for="star1half_${comment.id}" class="half" id="ratingDisable"></label>

                            <input type="radio" id="star1_${comment.id}" name="rating_${comment.id}" value="2" ${comment.rating === 2 ? 'checked' : ''}/>
                            <label for="star1_${comment.id}" class="full" id="ratingDisable" ></label>

                            <input type="radio" id="starhalf_${comment.id}" name="rating_${comment.id}" value="1" ${comment.rating === 1 ? 'checked' : ''}/>
                            <label for="starhalf_${comment.id}" class="half" id="ratingDisable"></label>
                        </fieldset>
                    </div>
                </div>
                <p class="review-content">${comment.description}</p>
                <div class="review-footer">
                    <span class="review-date">${new Date(comment.created_at).toLocaleDateString()}</span>
                </div>
            </div>
        `;
    });

    document.getElementById('comments').innerHTML = html;

    document.getElementById('prev').disabled = currentPage === 0;
    document.getElementById('next').disabled = (currentPage + 1) * perPage >= comments.length;

}

function nextComments() {
    if ((currentPage + 1) * perPage < comments.length) {
        currentPage++;
        showComments();
    }
}

function previousComments() {
    if (currentPage > 0) {
        currentPage--;
        showComments();
    }
}

fetchComments();

//them binh luan

document.addEventListener('DOMContentLoaded', function () {
    const starInputs = document.querySelectorAll('.rating input[type="radio"]');
    const scoreDisplay = document.querySelector('.rating p');
    let selectedRating = 0;

    starInputs.forEach(input => {
        const label = input.nextElementSibling;

        label.addEventListener('mouseover', function () {
            resetStars();
            highlightStars(input.value);
            scoreDisplay.textContent = `${input.value} điểm`;
        });

        label.addEventListener('mouseout', function () {
            resetStars();
            highlightStars(selectedRating);
            scoreDisplay.textContent = `${selectedRating} điểm`;
        });

        label.addEventListener('click', function () {
            selectedRating = input.value;
            highlightStars(selectedRating);
            scoreDisplay.textContent = `${selectedRating} điểm`;
        });
    });

    function highlightStars(rating) {
        starInputs.forEach(input => {
            const starValue = parseInt(input.value);
            const label = input.nextElementSibling;

            if (starValue <= rating) {
                label.classList.add('highlighted');
            } else {
                label.classList.remove('highlighted');
            }
        });
    }

    function resetStars() {
        starInputs.forEach(input => {
            const label = input.nextElementSibling;
            label.classList.remove('highlighted');
        });
    }

});

