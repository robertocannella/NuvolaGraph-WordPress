import {useState} from '@wordpress/element';
import {Availability} from "./DailyListGroup";

interface Props {
    heading: string;
    timeSlots: Availability[];
    onDelete: (item:Availability) => void;

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
export default function HourlyListGroup({timeSlots, heading, onDelete}: Props) {


    const [selectedItem, setSelectedItem] = useState(null)
    const handleClick = (item) => {
        setSelectedItem(item)
    }
    return (
        <>
            <h1 className={"font-bold"}>{heading} Slots: {timeSlots.length}<span className={"float-right"}>
                <i className="fa-solid fa-plus hover:text-green-400"></i>
            </span></h1>
            <ul className="">

                {timeSlots.map((slot,index) =>
                   (<>
                            <li
                                className={selectedItem === slot ? 'bg-violet-200 ' + listItemStyles : 'hover:bg-violet-300 ' + listItemStyles}
                                onClick={() => handleClick(slot)}
                                key={heading + index}>
                                    <label for="start-time" className={"font-thin"}>From: </label>
                                    <input
                                        style={ selectedItem === slot ? inputStyles.selected : inputStyles.default}
                                        type="text"
                                        name="start-time"
                                        value={slot.start_time}
                                           />
                                <label htmlFor="end-time" className={"font-thin"}>To: </label>

                                <input
                                        style={ selectedItem === slot ? inputStyles.selected : inputStyles.default}
                                        type="text"
                                        name="end-time"
                                        value={slot.end_time}
                                    />

                                 <span onClick={() => onDelete(slot)} className={"float-right"}>
                                <i className="fa-solid fa-trash text-lg hover:text-red-400"></i>
                            </span>

                            </li>

                        </>
                    )
                )}

            </ul>
        </>

    );
}