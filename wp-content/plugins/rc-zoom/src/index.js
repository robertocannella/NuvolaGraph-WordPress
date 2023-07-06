const { render } = wp.element; //we are using wp.element here!

import App from './App';
import {MyAvailability} from "./js/modules/MyAvailability";



document.addEventListener("DOMContentLoaded",()=>{
    if (document.getElementById('rc-zoom-react-app')) { //check if element exists before rendering
        render(<App />, document.getElementById('rc-zoom-react-app'));
    }
})


// Load Custom JS

const myAvailability = new MyAvailability();