<template>
  <div class="custom-select-site" :tabindex="tabindex" @blur="open = false">
    <div class="selected select-room" :class="{ open: open }" @click="open = !open">
      {{ selected }}
    </div>
    <div class="items" :class="{ selectHide: !open }">
      <div v-for="(option, i) of options" :key="i"
        @click="selected = option.name; open = false; $emit('input', option.id);">
        {{ option.name }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    options: {
      type: Array,
      required: true,
    },
    default: {
      type: String,
      required: false,
      default: null,
    },
    tabindex: {
      type: Number,
      required: false,
      default: 0,
    },
  },
  data() {
    return {
      selected: this.default
        ? this.default
        : this.options.length > 0
          ? this.options[0]
          : null,
      open: false,
    };
  },
  mounted() {
    this.$emit("input", this.selected);
  },
};
</script>

<style scoped>
.custom-select-site {
  position: relative;
  width: 100%;
  text-align: left;
  outline: none;
  height: 47px;
  line-height: 47px;
  font-weight: 800;
  font-size: 25px;
  color: #0c2610;
}

.custom-select-site .selected {
  background-color: #f2ebdc;
  border-radius: 6px;
  /* border: 3px solid #735439; */
  border: 3px solid #ad8225;
  color: #f2ebdc;
  text-align: center;
  cursor: pointer;
  user-select: none;
}

.custom-select-site .selected.open {
  color: #0c2610;
  background-color: #f2ebdc;
  /* border: 4px solid #735439; */
  border: 4px solid #ad8225;
  border-radius: 6px 6px 6px 6px;
}

.custom-select-site .selected:after {
  position: absolute;
  content: "";
  top: 22px;
  right: 1em;
  width: 0;
  height: 0;
  border: 5px solid transparent;
  border-color: #0c2610 transparent transparent transparent;
}

  .custom-select-site .items {
    color: #0c2610;
    border-radius: 0px 0px 6px 6px;
    overflow: hidden;
    /* border-right: 2px solid #ad8225;
    border-left: 2px solid #ad8225;
    border-bottom: 2px solid #ad8225; */
    border-right: 2px solid #735439;
    border-left: 2px solid #735439;
    border-bottom: 2px solid #735439;
    position: absolute;
    background-color: #fff;
    left: 0;
    right: 0;
    z-index: 2;
  }

.custom-select-site .items div {
  color: #0c2610;
  padding-left: 1em;
  cursor: pointer;
  user-select: none;
  z-index: 1;
}

.custom-select-site .items div:hover {
  background-color: #f2c6a0;
}

.selectHide {
  display: none;
}
.custom-placeholder{
  position: absolute; left: 35px; top: -18px; bottom: 36px; z-index: 1; font-weight: bold; font-size: 25px; color: rgb(42, 42, 42); background-color: rgb(242, 235, 220);
}
</style>
