<template>
  <Transition name="minimize" appear>
    <slot />
  </Transition>
</template>

<style>
.minimize-enter-active,
.minimize-leave-active {
  /* scale: 1.5; */
  transition: all 0.5s ease;
}

.minimize-enter-from,
.minimize-leave-to {
  scale: 0.0;
  translate: -50% 50%;
  transform: skew(-50deg);
}
</style>
