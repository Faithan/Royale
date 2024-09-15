<div class="header-icons-container">
    <i id="user-shield" class="fa-solid fa-user-shield"></i>
    <span id="tooltip" class="tooltip-text">Admin Settings</span>

    <i id="theme-toggle" class="fa-solid fa-lightbulb"></i>
</div>


<style>
   .header-icons-container {
    position: relative;
    display: inline-block;
}

#user-shield {
    position: relative;
    cursor: pointer;
}

.tooltip-text {
    position: absolute;
    top: 120%;  /* Position below the icon */
    left: 0;  /* Start the tooltip at the center */
    transform: translateX(-50%);  /* Center the tooltip horizontally */
    background-color: var(--first-bgcolor);
    color: var(--text-color);
    padding: 10px 10px;
    border-radius: 5px;
    font-size: 1.8rem;
    font-weight: bold;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

#user-shield:hover + .tooltip-text {
    opacity: 1;
    visibility: visible;
}

</style>

<script>
document.getElementById('user-shield').addEventListener('click', function() {
    window.location.href = "admin_settings.php"; // Redirect to Admin Settings
});
</script>