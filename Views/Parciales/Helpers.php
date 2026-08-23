<?php
function renderStarsHtml($rating, $maxStars = 5) {
    $rating = max(0, min($maxStars, (float) $rating));
    $percent = ($rating / $maxStars) * 100;

    $html = '<span class="star-rating-display">';
    $html .= '<span class="stars-empty">' . str_repeat('★', $maxStars) . '</span>';
    $html .= '<span class="stars-filled" style="width: ' . $percent . '%;">' . str_repeat('★', $maxStars) . '</span>';
    $html .= '</span>';

    return $html;
}