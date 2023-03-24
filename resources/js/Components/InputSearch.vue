<template>
    <div class="
                  h-10
                  w-auto
                  search-input
                  flex flex-row
                  justify-center
                  items-center
                  px-2  
                  my-4
                  border-b 
                  border-slate-200
                ">
        <Input id="search" type="text" :placeholder="placeholder" :value="modelValue" class="
                        p-0
                        px-2
                        h-full
                        border-none
                        bg-transparent
                        shadow-none
                        text-sm
                        font-normal
                        border-slate-200
                        focus:border-none focus:ring-transparent focus:ring-offset-transparent
                      " /> 
    </div>
</template>

<script>
import { defineComponent } from "vue";
import Input from "@/Components/Input.vue";

export default defineComponent({
    components: {
        Input,
    },
    props: {
        icon: {
            type: Boolean,
            default: true
        },
        modelValue: {
            type: String
        },
        placeholder: {
            type: String,
            default: ""
        }
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
});
</script>


<style scoped>
.search-input {
    width: 25%;
    box-shadow: 2px 2px 2px #ffffff, inset 1.5px 1.5px 2px rgba(0, 0, 0, 0.25);
    border-radius: 10px;
}
</style>

