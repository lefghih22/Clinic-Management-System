<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
  nav.main-nav {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #0A2472;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2.5rem;
    height: 68px;
    box-shadow: 0 4px 24px rgba(10, 36, 114, 0.25);
    font-family: 'DM Sans', sans-serif;
  }

  .nav-brand {
    font-family: 'Sora', sans-serif;
    font-weight: 800;
    font-size: 1.3rem;
    color: #ffffff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .brand-icon {
    width: 36px;
    height: 36px;
    background: #3B82F6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .nav-search {
    display: flex;
    gap: 8px;
  }

  .nav-search input {
    background: rgba(255, 255, 255, 0.12);
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    color: white;
    padding: 7px 16px;
    border-radius: 50px;
    font-size: 0.875rem;
    outline: none;
    width: 180px;
    transition: all 0.2s;
    font-family: 'DM Sans', sans-serif;
  }

  .nav-search input::placeholder {
    color: rgba(255, 255, 255, 0.45);
  }

  .nav-search input:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: #3B82F6;
  }

  .nav-search button {
    background: #3B82F6;
    color: white;
    border: none;
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    font-family: 'DM Sans', sans-serif;
  }

  .nav-search button:hover {
    background: #2563EB;
  }

  @media (max-width: 640px) {
    nav.main-nav {
      padding: 0 1.25rem;
    }

    .nav-search input {
      width: 120px;
    }
  }
</style>

<nav class="main-nav">
  <a class="nav-brand" href="index.php">
    <span class="brand-icon">🏥</span>
    Clinic Management
  </a>
  <div class="nav-search">
    <input type="search" placeholder="Search...">
    <button>Search</button>
  </div>
</nav>