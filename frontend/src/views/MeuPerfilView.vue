<template>
    <div class="profile-placeholder">
      <i class="fa-solid fa-gears icon"></i>
      <h1>Meu Perfil</h1>
      <p>
        Esta funcionalidade ainda não está disponível. Por favor, volte mais
        tarde.
      </p>
    </div>
  </template>
  
  <script>
  export default {
    name: "UserProfile",
  };
  </script>
  
  <style scoped>
  .profile-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: auto;
    gap: 20px;
    padding: 20px;
    width: 90%;
    height: 80vh;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    text-align: center;
  }
  
  .profile-placeholder .icon {
    font-size: 4em;
    color: #555;
  }
  
  .profile-placeholder h1 {
    font-size: 2em;
    margin-bottom: 20px;
  }
  
  .profile-placeholder p {
    font-size: 1.2em;
    color: #555;
  }
  </style>
  