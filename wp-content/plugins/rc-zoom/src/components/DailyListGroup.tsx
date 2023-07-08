import { useState, useEffect } from '@wordpress/element';
import availabilityService from "../services/availability-service";
import {CanceledError} from "../services/api-client";
import HourlyListGroup from "./HourlyListGroup";
import {tuesday_data} from "../App";

interface Props {
    heading: string;
    days: string[];
}
export interface Availability {
    day_of_week: string;
    start_date: string
    end_date: string
    start_time: string
    end_time: string
}

let listItemStyles = "w-full border-b-2 border-neutral-100 border-opacity-100 py-2 px-1 cursor-pointer dark:border-opacity-50";


export default function DailyListGroup({days, heading}: Props) {

    const [availability, setAvailability] = useState({});
    const [stateChanged, setStateChanged] = useState(false);
    const [error, setError] = useState("");
    const [isLoading, setLoading] = useState(false);

    useEffect(() => {
        setLoading(true);
        const { request, cancel } = availabilityService.get<Availability>(1);
        request
            .then((res) => {

                setLoading(false);
                // Grouping the objects by weekday
                const groupedData = res.data.data.reduce((acc, obj) => {
                    const { day_of_week } = obj;
                    if (!acc[day_of_week]) {
                        acc[day_of_week] = [];
                    }
                    acc[day_of_week].push(obj);
                    return acc;
                }, {});

                // Add days that are not set in the database
                days.forEach(day=> {

                    if (!groupedData[day]){
                       groupedData[day] = []
                    }
                })
                const desiredOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                const sortedObject = {};
                desiredOrder.forEach(day => {
                    if (groupedData.hasOwnProperty(day)) {
                        sortedObject[day] = groupedData[day];
                    }
                });
                setAvailability(sortedObject);



            })
            .catch((err) => {
                if (err instanceof CanceledError) return;
                setError(err.message);
                setLoading(false);
            });

        return () => cancel();
    }, []);

    const [selectedItem, setSelectedItem] = useState('')
    const handleClick = (item) => {
        setSelectedItem(item)
        console.log(item)
    }

    const handleOnDelete = (item) =>{
        // Set state has changed
        setStateChanged(true);

        // Get all the unchanged days from the state
        let updatedAvailability = {}
        Object.keys(availability)
            // iterate through the object
            .map(weekday=> weekday != item.day_of_week ?

                // if the items is not the one we want to filter, use the current
                updatedAvailability[weekday] = (availability[weekday]) :

                // if it is the item we want to filter, filter it.
                updatedAvailability[weekday] = filterAvailability(weekday,item)
            )
        // Update the state
        setAvailability(updatedAvailability)

    }
    const filterAvailability = (weekday,timeslot) =>{
        let filteredData = availability[weekday].filter(time => time != timeslot)
        return filteredData;
    }

    return (
        <>
            <h1 className={"text-lg"}>{heading} {stateChanged && <span className={"float-right hover:text-green-400 cursor-pointer"}>
                save <i className="fa-solid fa-save  "></i>
            </span>}
            </h1>
            <ul>

            {isLoading ? 'Loading...' :

                Object.keys(availability).map((weekday,index) =>

                    <li
                        className={selectedItem === weekday ? 'bg-violet-200 ' + listItemStyles: 'hover:bg-slate-200 ' + listItemStyles }
                        onClick={()=>handleClick(weekday)}
                        key={index}><h1>{selectedItem !== weekday && `${weekday} slots: ${availability[weekday].length}` }</h1>
                        {selectedItem === weekday &&
                        <ul>
                            <HourlyListGroup heading={weekday} timeSlots={availability[weekday]} onDelete={(item)=>handleOnDelete(item)}/>
                        </ul>}
                    </li>
            )}

            </ul>
        </>

    );
}