import {initializeApp} from 'firebase/app'
import { getMessaging } from 'firebase/messaging';


const firebaseConfig = {
    apiKey: "AIzaSyD5gB0xPY4uZOh_dw316UZhb7CoP6_cnJ4",
    authDomain: "flashtour-a7f37.firebaseapp.com",
    projectId: "flashtour-a7f37",
    storageBucket: "flashtour-a7f37.appspot.com",
    messagingSenderId: "850869559839",
    appId: "1:850869559839:web:bce4a2d2f4cda2de8a2b2a",
    measurementId: "G-FTPEDXMHYE"
  };

const firebaseApp = initializeApp(firebaseConfig)
const firebaseMessenger = getMessaging(firebaseApp);

export {
    firebaseMessenger
}

export default firebaseApp;
