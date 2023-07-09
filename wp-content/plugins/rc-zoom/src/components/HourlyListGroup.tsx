import {useState} from '@wordpress/element';
import {Availability} from "./DailyListGroup";

interface Props {
    heading: string;
    timeSlots: Availability[];
    isSelected: boolean;
    showAll: boolean;
    onDelete: (item:Availability) => void;
    onUpdateAvailability: (e, selectedItem:Availability,time:string) => void;

}

const inputStyles = {
    default: {
        width: '6rem',
        background: 'transparent',
        border: 'none',
        margin: '0px 2px 2px 0px'
    },
    selected: {
        width: '6rem',
        background: 'white',
        outline: 'none',
        border: 'none',
        margin: '0px 2px 2px 0px'

    },
};

let listItemStyles = "w-full border-b-2 border-neutral-100 border-opacity-100 py-2 px-1 cursor-pointer dark:border-opacity-50";

let inputItemStyles = "mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500";

const convertTo12HourFormat = (time) => {
    const [hours, minutes, seconds] = time.split(':');
    let formattedTime = '';

    if (Number(hours) > 12) {
        formattedTime = `${Number(hours) - 12}:${minutes}:${seconds} pm`;
    } else if (Number(hours) === 0) {
        formattedTime = `12:${minutes}:${seconds} am`;
    } else if (Number(hours) === 12) {
        formattedTime = `12:${minutes}:${seconds} pm`;
    } else {
        formattedTime = `${hours}:${minutes}:${seconds} am`;
    }

    return formattedTime;
};


export default function HourlyListGroup({timeSlots, heading, onDelete, onUpdateAvailability, isSelected, showAll}: Props) {

    const [selectedItem, setSelectedItem] = useState(null)
    const [endTimes, setEndTimes] = useState([]);
    const [startTimes, setStartTimes] = useState([]);
    const handleClick = (item) => {
        setSelectedItem(item)
    }
    const validateField = (e: React.FocusEvent<HTMLInputElement>) => {
        setSelectedItem(null);
        //console.log(e.target.value)
    }
    return (
        <>
            {(isSelected || showAll) &&
            <h1 className={"font-bold"}>
                {heading} Slots: {timeSlots.length}
                <span className={"float-right"}>
                     <i className="fa-solid fa-plus hover:text-green-400"></i>
                </span>
            </h1>}
            <ul className="">

                {(isSelected || showAll) && timeSlots.map((slot,index) =>
                   (<form>

                            <li
                                // className={selectedItem === slot ? 'bg-violet-200 ' + listItemStyles : 'hover:bg-violet-300 ' + listItemStyles}

                                key={heading + index}>
                                    <label for="start_time" className={"font-thin"}>From: </label>
                                    
                                    <input
                                        onFocus={() => handleClick(slot)}
                                        style={ selectedItem === slot ? inputStyles.selected : inputStyles.default}
                                        type="time"
                                        id={slot.id}
                                        name={"start_time"}
                                        value={slot.start_time}
                                        onBlur={(e)=> validateField(e)}
                                        onChange={(e)=>onUpdateAvailability(e,selectedItem,"start_time")}
                                           />

                                <label htmlFor="end-time" className={"font-thin"}>To: </label>

                                <input
                                        onFocus={() => handleClick(slot)}
                                        style={ selectedItem === slot ? inputStyles.selected : inputStyles.default}
                                        type="time"
                                        id={slot.id}
                                        name={"end_time"}
                                        value={slot.end_time}
                                        onBlur={(e)=> validateField(e)}
                                        onChange={(e)=>onUpdateAvailability(e,selectedItem,"end_time")}
                                    />

                                 <span onClick={() => onDelete(slot)} className={"float-right"}>
                                <i className="fa-solid fa-trash text-lg hover:text-red-400"></i>
                            </span>

                            </li>

                        </form>
                    )
                )}

            </ul>
        </>

    );
}