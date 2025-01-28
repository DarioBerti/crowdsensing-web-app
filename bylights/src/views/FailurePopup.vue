<template>
    <div class="popup-overlay" @click.self="closePopup">
      <div class="popup-content">
        <p>Tempo di registrazione troppo corto!</p>
      </div>
    </div>
</template>
  
<script>
export default {
  name: 'FailurePopup',

  setup(props, { emit }) {

    const closePopup = () => {
      emit('close');
    };

    return {
      closePopup,
    };
  },
};
</script>
  
  <style scoped>
  .popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .popup-content {
    background-color: #fff;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    max-width: 300px;
    width: 80%;
  }
  
  .popup-content p {
    font-family:;
    font-size: 18px;
    margin: 0;
    font-weight: bolder;
    color: rgb(170, 7, 7);
  }
  </style>
  