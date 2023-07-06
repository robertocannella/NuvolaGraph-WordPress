import { useState } from '@wordpress/element';
import SubListGroup from "./SubListGroup";
import HourlyListGroup from "./HourlyListGroup";

interface Props {
    heading: string;
    days: string[];
}


let listItemStyles = "w-full border-b-2 border-neutral-100 border-opacity-100 py-2 px-1 cursor-pointer dark:border-opacity-50";




export default function DailyListGroup({days, heading}: Props) {

    const [times, setTimes] = useState(["time1","time2","time3"]);


    const [selectedItem, setSelectedItem] = useState('')
    const handleClick = (item) => {
        setSelectedItem(item)
        console.log(item)
    }
    const handleOnDelete = (item) =>{
        console.log("filtering Item: ", item)
        let updatedTimes = times.filter(i => i != item);
        setTimes([...updatedTimes]);
    }


    return (
        <>
            <h1 className={"text-lg"}>{heading}</h1>
            <ul className="">
                {days.map((day,index) =>
                    (<li

                        className={selectedItem === day ? 'bg-violet-200 ' + listItemStyles: 'hover:bg-slate-200 ' + listItemStyles }
                        onClick={()=>handleClick(day)}
                        key={index}>{(selectedItem !== day) && day }{selectedItem === day &&
                        <HourlyListGroup key={heading + day + index} heading={day} timeSlots={times} onDelete={handleOnDelete}/>
                        }
                    </li>)
                )}

            </ul>
        </>

    );
}