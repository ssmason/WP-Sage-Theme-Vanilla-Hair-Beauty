
 import {
  adoptMasonryGridLanesStyles,
  defineMasonryGridLanes,
} from "@schalkneethling/masonry-gridlanes-wc";

defineMasonryGridLanes();
(async () => {
    await adoptMasonryGridLanesStyles(document);
})();
