<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
  url = new URL(window.location.href);
  url.searchParams.delete("message");
  window.history.pushState('', '', url);
</script>

</html>