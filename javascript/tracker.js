const startTime = Date.now();

window.addEventListener("beforeunload", function () {
  const endTime = Date.now();
  const duration = Math.round((endTime - startTime) / 1000);

  navigator.sendBeacon("/php/track_view.php", JSON.stringify({
    user_id: window.loggedInUser || null,
    page_visited: window.location.pathname + window.location.search,
    product_id: window.productId || null,
    session_start: new Date(startTime).toISOString().slice(0, 19).replace("T", " "),
    session_end: new Date(endTime).toISOString().slice(0, 19).replace("T", " "),
    duration_seconds: duration
  }));
});
