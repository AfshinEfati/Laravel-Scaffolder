import _sfc_main$1 from "./ContentQuery-C2tgz5RD.js";
import _sfc_main$2 from "./ContentRenderer-zE7hn5dv.js";
import { defineComponent, useSlots, h, useSSRContext } from "vue";
import { withTrailingSlash } from "/app/docs-site/node_modules/ufo/dist/index.mjs";
import { b as useRuntimeConfig, a as useRoute } from "../server.mjs";
import { u as useContentHead } from "./head-3IpIxPIF.js";
import "/app/docs-site/node_modules/ohash/dist/index.mjs";
import "./query-RT5V1MJ1.js";
import "/app/docs-site/node_modules/perfect-debounce/dist/index.mjs";
import "/app/docs-site/node_modules/hookable/dist/index.mjs";
import "/app/docs-site/node_modules/defu/dist/defu.mjs";
import "/app/docs-site/node_modules/klona/dist/index.mjs";
import "./preview-CxS_JB1H.js";
import "/app/docs-site/node_modules/nuxt/node_modules/cookie-es/dist/index.mjs";
import "/app/docs-site/node_modules/h3/dist/index.mjs";
import "/app/docs-site/node_modules/destr/dist/index.mjs";
import "/app/docs-site/node_modules/nuxt/node_modules/ohash/dist/index.mjs";
import "./ContentRendererMarkdown-DIHkEoZ6.js";
import "/app/docs-site/node_modules/scule/dist/index.mjs";
import "property-information";
import "./node-DPfXEbjB.js";
import "vue/server-renderer";
import "/app/docs-site/node_modules/ofetch/dist/node.mjs";
import "#internal/nuxt/paths";
import "/app/docs-site/node_modules/unctx/dist/index.mjs";
import "vue-router";
import "/app/docs-site/node_modules/nuxt/node_modules/@unhead/vue/dist/index.mjs";
const ContentDoc = defineComponent({
  name: "ContentDoc",
  props: {
    /**
     * Renderer props
     */
    /**
     * The tag to use for the renderer element if it is used.
     * @default 'div'
     */
    tag: {
      type: String,
      required: false,
      default: "div"
    },
    /**
     * Whether or not to render the excerpt.
     * @default false
     */
    excerpt: {
      type: Boolean,
      default: false
    },
    /**
     * Query props
     */
    /**
     * The path of the content to load from content source.
     * @default useRoute().path
     */
    path: {
      type: String,
      required: false,
      default: void 0
    },
    /**
     * A query builder params object to be passed to <ContentQuery /> component.
     */
    query: {
      type: Object,
      required: false,
      default: void 0
    },
    /**
     * Whether or not to map the document data to the `head` property.
     */
    head: {
      type: Boolean,
      required: false,
      default: void 0
    }
  },
  /**
   * Document empty fallback
   * @slot empty
   */
  /**
   * Document not found fallback
   * @slot not-found
   */
  render(ctx) {
    const { contentHead } = useRuntimeConfig().public.content;
    const slots = useSlots();
    const { tag, excerpt, path, query, head } = ctx;
    const shouldInjectContentHead = head === void 0 ? contentHead : head;
    const contentQueryProps = {
      ...query || {},
      path: path || query?.path || withTrailingSlash(useRoute().path),
      find: "one"
    };
    const emptyNode = (slot, data) => h("pre", null, JSON.stringify({ message: "You should use slots with <ContentDoc>", slot, data }, null, 2));
    return h(
      _sfc_main$1,
      contentQueryProps,
      {
        // Default slot
        default: slots?.default ? ({ data, refresh, isPartial }) => {
          if (shouldInjectContentHead) {
            useContentHead(data);
          }
          return slots.default?.({ doc: data, refresh, isPartial, excerpt, ...this.$attrs });
        } : ({ data }) => {
          if (shouldInjectContentHead) {
            useContentHead(data);
          }
          return h(
            _sfc_main$2,
            { value: data, excerpt, tag, ...this.$attrs },
            // Forward local `empty` slots to ContentRenderer if it is used.
            { empty: (bindings) => slots?.empty ? slots.empty(bindings) : emptyNode("default", data) }
          );
        },
        // Empty slot
        empty: (bindings) => slots?.empty?.(bindings) || h("p", null, "Document is empty, overwrite this content with #empty slot in <ContentDoc>."),
        // Not Found slot
        "not-found": (bindings) => slots?.["not-found"]?.(bindings) || h("p", null, "Document not found, overwrite this content with #not-found slot in <ContentDoc>.")
      }
    );
  }
});
const _sfc_main = ContentDoc;
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("node_modules/@nuxt/content/dist/runtime/components/ContentDoc.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
//# sourceMappingURL=ContentDoc-DTAPhitW.js.map
